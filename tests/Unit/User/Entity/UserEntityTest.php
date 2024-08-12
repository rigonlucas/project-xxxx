<?php

namespace Tests\Unit\User\Entity;

use Core\Domain\Entities\User\UserEntity;
use Infra\Dependencies\Framework\Framework;
use Tests\TestCase;

/**
 * @group UserEntity
 */
class UserEntityTest extends TestCase
{
    public function test_deve_retornar_que_o_usuario_nao_tem_idade_legal(): void
    {
        // Arrange
        $userEntity = UserEntity::forCreate(
            name: 'John Doe',
            email: '',
            password: 'password',
            uuid: Framework::getInstance()->uuid()->uuid7Generate(),
            birthday: now()->subYears(17)
        );
        $this->assertTrue($userEntity->hasNoLegalAge());
    }

    public function test_deve_retornar_nome_do_usuario(): void
    {
        // Arrange
        $userEntity = UserEntity::forCreate(
            name: 'John Doe',
            email: 'john@email.com',
            password: 'password',
            uuid: Framework::getInstance()->uuid()->uuid7Generate(),
            birthday: now()->subYears(18)
        );
        // Act
        $this->assertFalse($userEntity->hasNoLegalAge());
        $name = $userEntity->getName();

        // Assert
        $this->assertEquals('John Doe', $name);
    }
}
