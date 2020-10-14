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
class UpdateOperation extends Operation implements UpdateOperationInterface, OperationInterface
{
    const TYPE = 'update';

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
        parent::__construct($target);
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

        if ($fromVersion === $toVersion) {
            $displayMode = null;

            if ($initialPackage->getSourceReference() !== $targetPackage->getSourceReference()) {
                $displayMode = PackageInterface::DISPLAY_SOURCE_REF;
            } elseif ($initialPackage->getDistReference() !== $targetPackage->getDistReference()) {
                $displayMode = PackageInterface::DISPLAY_DIST_REF;
            }

            if ($displayMode !== null) {
                $fromVersion = $initialPackage->getFullPrettyVersion(true, $displayMode);
                $toVersion = $targetPackage->getFullPrettyVersion(true, $displayMode);
            }
        }

        $actionName = VersionParser::isUpgrade(
            $initialPackage->getVersion(),
            $targetPackage->getVersion()
        ) ? 'Upgrading' : 'Downgrading';

        return sprintf(
            '%s <info>%s</info> (<comment>%s</comment> => <comment>%s</comment>)',
            $actionName,
            $initialPackage->getPrettyName(),
            $fromVersion,
            $toVersion
        );
    }
}
