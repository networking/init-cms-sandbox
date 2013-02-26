<?php


namespace Sandbox\InitCmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Networking\InitCmsBundle\Entity\Page;
use Sandbox\InitCmsBundle\Form\UserType;

class WelcomeController extends Controller
{
    private $application;

    /**
     * @Route("/welcome", name="_configure_cms")
     * @Template()
     */
    public function indexAction()
    {

        try {
            /** @var $page Page */
            $page = $this->getDoctrine()->getRepository('NetworkingInitCmsBundle:Page')->findOneBy(array('isHome' => 1, 'locale' => $this->getRequest()->getLocale()));
            $url = $page->getFullPath();
            $label = 'Go to the homepage';
        } catch (\Exception $e) {
            $url = $this->generateUrl('_install_db');
            $label = 'Install the DB';
        }

        return $this->render('SandboxInitCmsBundle:Welcome:index.html.twig', array('action' => array('url' => $url, 'label' => $label)));
    }

    /**
     * @Route("/install_db", name="_install_db")
     * @Template()
     */
    public function installDbAction(Request $request)
    {
        try {
            /** @var $page Page */
            $page = $this->getDoctrine()->getRepository('NetworkingInitCmsBundle:Page')->findOneBy(array('isHome' => 1, 'locale' => $this->getRequest()->getLocale()));
            return new RedirectResponse($this->generateUrl('_configure_cms'));
        } catch (\Exception $e) {
            // do nothing
        }

        $form = $this->get('form.factory')->create(new UserType());

        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {

                $kernel = $this->get('kernel');
                $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
                $application->setAutoExit(false);

                $this->setApplication($application);
                $formData = $request->get('user');
                $username = $formData['username'];
                $email = $formData['email'];
                $password = $formData['password'];

                $output = new \Symfony\Component\Console\Output\ConsoleOutput();
                $this->createDB($output) ;
                $this->initACL($output);
                $this->sonataSetupACL($output);
                $this->loadFixtures($output);
                $this->createAdminUser($output, $username, $email, $password);
                $this->dumpAssetic($output);
                $this->get('session')->setFlash('notice', 'CMS is setup');

                $message = \Swift_Message::newInstance()
                    ->setSubject('Hello Email')
                    ->setFrom('send@example.com')
                    ->setTo($email)
                    ->setBody(
                    $this->renderView(
                        'SandboxInitCmsBundle:Welcome:email.txt.twig',
                        array('name' => $username, 'password' =>$password)
                    )
                )
                ;
                $this->get('mailer')->send($message);

                return new RedirectResponse($this->generateUrl('_configure_cms'));
            }
        }

        return $this->render('SandboxInitCmsBundle:Welcome:index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|string
     */
    private function createDB(OutputInterface $output)
    {
//        $command = $this->getApplication()->find('doctrine:schema:update');

        $arguments = array(
            'command' => 'doctrine:schema:update',
            '--force' => true,
        );

        $input = new ArrayInput($arguments);

        return $this->getApplication()->run($input);
    }

    /**
     * @param $output
     * @return int
     */
    private function initACL($output)
    {

        $arguments = array(
            'command' => 'init:acl'
        );

        $input = new ArrayInput($arguments);

        return $this->getApplication()->run($input, $output);
    }

    /**
     * @param $output
     * @return int
     */
    private function sonataSetupACL($output)
    {

        $arguments = array(
            'command' => 'sonata:admin:setup-acl'
        );

        $input = new ArrayInput($arguments);

        return $this->getApplication()->run($input, $output);
    }


    /**
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|string
     */
    private function dumpAssetic(OutputInterface $output)
    {


        $arguments = array(
            'command' => 'assetic:dump',
            '--env' => 'prod',
            '--no-debug' => true
        );

        $input = new ArrayInput($arguments);

        return $this->getApplication()->run($input, $output);
    }

    /**
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|string
     */
    private function createAdminUser(OutputInterface $output, $username, $email, $password)
    {

        $arguments = array(
            'command' => 'fos:user:create',
            'username' => $username,
            'email' => $email,
            'password' => $password,
            '--super-admin' => true,
        );

        $input = new ArrayInput($arguments);

        return $this->getApplication()->run($input, $output);
    }

    /**
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|string
     */
    private function loadFixtures(OutputInterface $output)
    {
        $arguments = array(
            'command' => 'doctrine:fixtures:load',
            '--fixtures' => __DIR__ . '/../../../../vendor/networking/init-cms-bundle/Networking/InitCmsBundle/Fixtures',
            '--append' => true
        );

        $input = new ArrayInput($arguments);

        return $this->getApplication()->run($input, $output);
    }

    private function setApplication($application)
    {
        $this->application = $application;
    }

    private function getApplication()
    {
        return $this->application;
    }
}
