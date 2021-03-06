<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\DependencyResolver\Operation;

use Composer\Package\PackageInterface;
use Composer\Package\Version\VersionParser;

/**
 * Solver update operation.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class UpdateOperation implements OperationInterface
{
    protected $initialPackage;
    protected $targetPackage;

    /**
     * Initializes update operation.
     *
     * @param PackageInterface $initial initial package
     * @param PackageInterface $target  target package (updated)
     */
    public function __construct(PackageInterface $initial, PackageInterface $target)
    {
        $this->initialPackage = $initial;
        $this->targetPackage = $target;
    }

    /**
     * Returns initial package.
     *
     * @return PackageInterface
     */
    public function getInitialPackage()
    {
        return $this->initialPackage;
    }

    /**
     * Returns target package.
     *
     * @return PackageInterface
     */
    public function getTargetPackage()
    {
        return $this->targetPackage;
    }

    /**
     * Returns operation type.
     *
     * @return string
     */
    public function getOperationType()
    {
        return 'update';
    }

    /**
     * {@inheritDoc}
     */
    public function show($lock)
    {
        return self::format($this->initialPackage, $this->targetPackage, $lock);
    }

    public static function format(PackageInterface $initialPackage, PackageInterface $targetPackage, $lock = false)
    {
        $fromVersion = $initialPackage->getFullPrettyVersion();
        $toVersion = $targetPackage->getFullPrettyVersion();

        if ($fromVersion === $toVersion && $initialPackage->getSourceReference() !== $targetPackage->getSourceReference()) {
            $fromVersion = $initialPackage->getFullPrettyVersion(true, PackageInterface::DISPLAY_SOURCE_REF);
            $toVersion = $targetPackage->getFullPrettyVersion(true, PackageInterface::DISPLAY_SOURCE_REF);
        } elseif ($fromVersion === $toVersion && $initialPackage->getDistReference() !== $targetPackage->getDistReference()) {
            $fromVersion = $initialPackage->getFullPrettyVersion(true, PackageInterface::DISPLAY_DIST_REF);
            $toVersion = $targetPackage->getFullPrettyVersion(true, PackageInterface::DISPLAY_DIST_REF);
        }

        $actionName = VersionParser::isUpgrade($initialPackage->getVersion(), $targetPackage->getVersion()) ? 'Upgrading' : 'Downgrading';

        return $actionName.' <info>'.$initialPackage->getPrettyName().'</info> (<comment>'.$fromVersion.'</comment> => <comment>'.$toVersion.'</comment>)';
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->show(false);
    }
}
