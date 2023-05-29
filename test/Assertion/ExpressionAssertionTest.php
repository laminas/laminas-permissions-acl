<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Assertion\Exception\InvalidAssertionException;
use Laminas\Permissions\Acl\Assertion\ExpressionAssertion;
use Laminas\Permissions\Acl\Exception\RuntimeException;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;
use LaminasTest\Permissions\Acl\TestAsset\ExpressionUseCase\BlogPost;
use LaminasTest\Permissions\Acl\TestAsset\ExpressionUseCase\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function serialize;

class ExpressionAssertionTest extends TestCase
{
    public function testFromPropertiesCreation(): void
    {
        $assertion = ExpressionAssertion::fromProperties(
            'foo',
            '=',
            'bar'
        );

        $this->assertInstanceOf(ExpressionAssertion::class, $assertion);
    }

    public function testFromArrayCreation(): void
    {
        $assertion = ExpressionAssertion::fromArray([
            'left'     => 'foo',
            'operator' => ExpressionAssertion::OPERATOR_EQ,
            'right'    => 'bar',
        ]);

        $this->assertInstanceOf(ExpressionAssertion::class, $assertion);
    }

    public function testExceptionIsRaisedInCaseOfInvalidExpressionArray(): void
    {
        $this->expectException(InvalidAssertionException::class);
        $this->expectExceptionMessage("Expression assertion requires 'left', 'operator' and 'right' to be supplied");

        ExpressionAssertion::fromArray(['left' => 'test', 'foo' => 'bar']);
    }

    public function testExceptionIsRaisedInCaseOfInvalidExpressionContextOperandType(): void
    {
        $this->expectException(InvalidAssertionException::class);
        $this->expectExceptionMessage('Expression assertion context operand must be string');

        ExpressionAssertion::fromProperties(
            [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 123],
            ExpressionAssertion::OPERATOR_IN,
            'test'
        );
    }

    public function testExceptionIsRaisedInCaseOfInvalidExpressionOperator(): void
    {
        $this->expectException(InvalidAssertionException::class);
        $this->expectExceptionMessage('Provided expression assertion operator is not supported');

        ExpressionAssertion::fromProperties(
            'test',
            'invalid',
            'test'
        );
    }

    /**
     * @param array<string, mixed> $expression
     */
    #[DataProvider('getExpressions')]
    public function testExpressionsEvaluation(
        array $expression,
        RoleInterface $role,
        ResourceInterface $resource,
        string $privilege,
        bool $expectedAssert
    ) {
        $assertion = ExpressionAssertion::fromArray($expression);

        $this->assertThat(
            $assertion->assert(new Acl(), $role, $resource, $privilege),
            $expectedAssert ? $this->isTrue() : $this->isFalse()
        );
    }

    /** @return array<string, array {
     *      expression: array<string, mixed>,
     *      role: RoleInterface,
     *      resource: ResourceInterface,
     *      privilege: string,
     *      assert: bool
     * }>
     */
    public static function getExpressions(): array
    {
        $author3 = new User([
            'username' => 'author3',
        ]);
        $post3   = new BlogPost([
            'author' => $author3,
        ]);

        return [
            'equality'                     => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_EQ,
                    'right'    => 'test',
                ],
                'role'       => new User([
                    'username' => 'test',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'inequality'                   => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_NEQ,
                    'right'    => 'test',
                ],
                'role'       => new User([
                    'username' => 'foobar',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'boolean-equality'             => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_EQ,
                    'right'    => true,
                ],
                'role'       => $author3,
                'resource'   => $post3,
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'greater-than'                 => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.age'],
                    'operator' => ExpressionAssertion::OPERATOR_GT,
                    'right'    => 20,
                ],
                'role'       => new User([
                    'username' => 'foobar',
                    'age'      => 15,
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => false,
            ],
            'greater-than-or-equal'        => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.age'],
                    'operator' => ExpressionAssertion::OPERATOR_GTE,
                    'right'    => 20,
                ],
                'role'       => new User([
                    'username' => 'foobar',
                    'age'      => 20,
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'less-than'                    => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.age'],
                    'operator' => ExpressionAssertion::OPERATOR_LT,
                    'right'    => 30,
                ],
                'role'       => new User([
                    'username' => 'foobar',
                    'age'      => 20,
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'less-than-or-equal'           => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.age'],
                    'operator' => ExpressionAssertion::OPERATOR_LTE,
                    'right'    => 30,
                ],
                'role'       => new User([
                    'username' => 'foobar',
                    'age'      => 30,
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'in'                           => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_IN,
                    'right'    => ['foo', 'bar'],
                ],
                'role'       => new User([
                    'username' => 'test',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => false,
            ],
            'not-in'                       => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_NIN,
                    'right'    => ['foo', 'bar'],
                ],
                'role'       => new User([
                    'username' => 'test',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'regex'                        => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_REGEX,
                    'right'    => '/foobar/',
                ],
                'role'       => new User([
                    'username' => 'test',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => false,
            ],
            'REGEX'                        => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'resource.shortDescription'],
                    'operator' => 'REGEX',
                    'right'    => '/ipsum/',
                ],
                'role'       => new User([
                    'username' => 'test',
                ]),
                'resource'   => new BlogPost([
                    'title'            => 'Test',
                    'content'          => 'lorem ipsum dolor sit amet',
                    'shortDescription' => 'lorem ipsum',
                ]),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'nregex'                       => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_NREGEX,
                    'right'    => '/barbaz/',
                ],
                'role'       => new User([
                    'username' => 'test',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'same'                         => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_SAME,
                    'right'    => 'test',
                ],
                'role'       => new User([
                    'username' => 'test',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'not-same'                     => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
                    'operator' => ExpressionAssertion::OPERATOR_NSAME,
                    'right'    => 'test',
                ],
                'role'       => new User([
                    'username' => 'foobar',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'equality-calculated-property' => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.adult'],
                    'operator' => ExpressionAssertion::OPERATOR_EQ,
                    'right'    => true,
                ],
                'role'       => new User([
                    'username' => 'test',
                    'age'      => 30,
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'read',
                'assert'     => true,
            ],
            'privilege'                    => [
                'expression' => [
                    'left'     => [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'privilege'],
                    'operator' => ExpressionAssertion::OPERATOR_EQ,
                    'right'    => 'read',
                ],
                'role'       => new User([
                    'username' => 'test',
                ]),
                'resource'   => new BlogPost(),
                'privilege'  => 'update',
                'assert'     => false,
            ],
        ];
    }

    public function testExceptionIsRaisedInCaseOfUnknownContextOperand(): void
    {
        $assertion = ExpressionAssertion::fromProperties(
            [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'foobar'],
            ExpressionAssertion::OPERATOR_EQ,
            'test'
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("'foobar' is not available in the assertion context");

        $assertion->assert(new Acl(), new User(), new BlogPost(), 'read');
    }

    public function testExceptionIsRaisedInCaseOfUnknownContextOperandContainingPropertyPath(): void
    {
        $assertion = ExpressionAssertion::fromProperties(
            [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'foo.bar'],
            ExpressionAssertion::OPERATOR_EQ,
            'test'
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("'foo' is not available in the assertion context");

        $assertion->assert(new Acl(), new User(), new BlogPost(), 'read');
    }

    public function testExceptionIsRaisedIfContextObjectPropertyCannotBeResolved(): void
    {
        $assertion = ExpressionAssertion::fromProperties(
            [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.age123'],
            ExpressionAssertion::OPERATOR_EQ,
            30
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("'age123' property cannot be resolved on the 'role' object");

        $assertion->assert(new Acl(), new User(), new BlogPost(), 'read');
    }

    public function testExceptionIsRaisedInCaseThatAssertHasBeenInvokedWithoutPassingContext(): void
    {
        $assertion = ExpressionAssertion::fromProperties(
            [ExpressionAssertion::OPERAND_CONTEXT_PROPERTY => 'role.username'],
            ExpressionAssertion::OPERATOR_EQ,
            'test'
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("'role' is not available in the assertion context");

        $assertion->assert(new Acl());
    }

    public function testSerialization(): void
    {
        $assertion = ExpressionAssertion::fromProperties(
            'foo',
            ExpressionAssertion::OPERATOR_EQ,
            'bar'
        );

        $serializedAssertion = serialize($assertion);

        $this->assertStringContainsString('left', $serializedAssertion);
        $this->assertStringContainsString('foo', $serializedAssertion);
        $this->assertStringContainsString('operator', $serializedAssertion);
        $this->assertStringContainsString('=', $serializedAssertion);
        $this->assertStringContainsString('right', $serializedAssertion);
        $this->assertStringContainsString('bar', $serializedAssertion);
    }

    public function testSerializationShouldNotSerializeAssertContext(): void
    {
        $assertion = ExpressionAssertion::fromProperties(
            'foo',
            ExpressionAssertion::OPERATOR_EQ,
            'bar'
        );

        $serializedAssertion = serialize($assertion);

        $this->assertStringNotContainsString('assertContext', $serializedAssertion);
    }
}
