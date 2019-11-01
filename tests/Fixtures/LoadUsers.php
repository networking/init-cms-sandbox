<?php
/**
 * This file is part of the Networking package.
 *
 * (c) net working AG <info@networking.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Fixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class LoadUsers
 * @packageTests\Fixtures
 * @author Yorkie Chadwick <y.chadwick@networking.ch>
 */
class LoadUsers extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('foo');
        $user->setUsernameCanonical('foo');
        $user->setEmail('foo@bar.com');
        $user->setEmailCanonical('foo@bar.com');
        $user->setPlainPassword('bar');
        $user->setEnabled((bool) true);
        $user->setSuperAdmin((bool) true);

        $manager->persist($user);
        $manager->flush();

    }


    /**
     * @return int
     */
    public function getOrder()
    {
        return 6;
    }
}
