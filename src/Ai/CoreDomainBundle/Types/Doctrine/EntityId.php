<?php
// EntityId.php
/**
 * User: aivanov
 * Date: 20.12.16
 * Time: 12:38
 */
namespace Ai\CoreDomainBundle\Types\Doctrine;

use Doctrine\DBAL\{
    Platforms\AbstractPlatform,
    Types\GuidType
};

abstract class EntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return $value;
        }
        return strval($value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return $value;
        }
        $className = $this->getNamespace().'\\'.$this->getName();

        return new $className($value);
    }

    abstract protected function getNamespace();

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return $sqlExpr;
    }
}