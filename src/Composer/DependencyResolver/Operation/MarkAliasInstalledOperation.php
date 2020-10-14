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

use Composer\Package\AliasPackage;

/**
 * Solver install operation.
 *
 * @author Nils Adermann <naderman@naderman.de>
 */
class MarkAliasInstalledOperation extends Operation implements OperationInterface
{
    const TYPE = 'markAliasInstalled';

    /**
     * Initializes operation.
     *
     * @param AliasPackage $package package instance
     */
    public function __construct(AliasPackage $package)
    {
        parent::__construct($package);
    }

    /**
     * {@inheritDoc}
     */
    public function show($lock)
    {
        return sprintf(
            'Marking <info>%s</info> (<comment>%s</comment>) as installed, alias of <info>%s</info> (<comment>%s</comment>)',
            $this->package->getPrettyName(),
            $this->package->getFullPrettyVersion(),
            $this->package->getAliasOf()->getPrettyName(),
            $this->package->getAliasOf()->getFullPrettyVersion()
        );
    }
}
