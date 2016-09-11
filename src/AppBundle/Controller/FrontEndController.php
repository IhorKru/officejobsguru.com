<?php

namespace AppBundle\Controller;

use DateTime;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\SubscriberDetails;
use AppBundle\Entity\SubscriberOptInDetails;
use AppBundle\Entity\SubscriberOptOutDetails;
use AppBundle\Entity\Contact;
use AppBundle\Form\SubscriberType;
use AppBundle\Form\SubscriberOptOutType;
use AppBundle\Form\ContactType;
use Swift_Message;

class FrontEndController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $error = 0;
        try{
            $newSubscriber = new SubscriberDetails();
            $exsSubscriber = new SubscriberDetails();
            $newOptInDetails = new SubscriberOptInDetails();
                $newSubscriber ->getOptindetails() ->add($newOptInDetails);
            
            $form1 = $this->createForm(SubscriberType::class, $newSubscriber, [
                'action' => $this -> generateUrl('index'),
                'method' => 'POST'
                ]);
            
            $form1->handleRequest($request);
            
            if($form1->isValid() && $form1->isSubmitted())
            {
                //getting data from the form
                $firstname = $form1['firstname']->getData();
                $lastname = $form1['lastname']->getData();
                $emailaddress = $form1['emailaddress']->getData();
                $phone = $form1['phone']->getData();
                $age = $form1['age']->getData();
                foreach ($form1->get('optindetails') as $subForm) {
                    $agreeterms = $subForm['agreeterms']->getData();
                }
                foreach ($form1->get('optindetails') as $subForm) {
                    $agreeemails = $subForm['agreeemails']->getData();
                }
                foreach ($form1->get('optindetails') as $subForm) {
                    $agreepartners = $subForm['agreepartners']->getData();
                }
                $hash = $this->mc_encrypt($newSubscriber->getEmailAddress(), $this->generateKey(16));
                $em = $this->getDoctrine()->getManager('custom');
                $exsSubscriber = $em->getRepository('AppBundle:SubscriberDetails') ->findOneBy(['emailaddress' => $emailaddress]);
                if(!$exsSubscriber) {
                    //if user does not exist -> collect user details
                    $query = $em ->createQuery('SELECT MAX(s.id) FROM AppBundle:SubscriberDetails s');
                    $newSubscriber ->setId($query->getSingleScalarResult() + 1);
                    $newSubscriber ->setFirstname($firstname);
                    $newSubscriber ->setLastname($lastname);
                    $newSubscriber ->setEmailaddress($emailaddress);
                    $newSubscriber ->setPhone($phone);
                    $newSubscriber ->setAge($age);
                    $newSubscriber ->setGender(-1);
                    $newSubscriber ->setEducationLevelId(-1);
                    $newSubscriber ->setHash($hash);
                    $newSubscriber ->setSourceid(1);
                    //if user does not exist -> collect user optin details
                    $query1 = $em ->createQuery('SELECT MAX(t.id) FROM AppBundle:SubscriberOptIndetails t');
                    $newOptInDetails ->setId($query1->getSingleScalarResult() + 1);
                    $newOptInDetails ->setUser($newSubscriber);
                    $newOptInDetails ->setResourceid(5);
                    $newOptInDetails ->setAgreeterms($agreeterms);
                    $newOptInDetails ->setAgreeemails($agreeemails);
                    $newOptInDetails ->setAgreepartners($agreepartners);
                    //pusshing data through to the database
                    $em->persist($newSubscriber);
                    $em->persist($newOptInDetails);
                    $em->flush();
                } else {
                    //if user does not exist for this resource
                    $userid = $exsSubscriber ->getId();
                    $isopted = $em ->getRepository('AppBundle:SubscriberOptInDetails') ->findOneBy(['user' => $userid, 'resourceid' => 5]);
                    if(!$isopted) {
                        //if user does not exist under other resources as well -> collect optin details
                        $query2 = $em ->createQuery('SELECT MAX(t.id) FROM AppBundle:SubscriberOptInDetails t');
                        $newOptInDetails ->setId($query2->getSingleScalarResult() + 1);
                        $newOptInDetails ->setUser($exsSubscriber);
                        $newOptInDetails ->setResourceid(5);
                        $newOptInDetails ->setAgreeterms($agreeterms);
                        $newOptInDetails ->setAgreeemails($agreeemails);
                        $newOptInDetails ->setAgreepartners($agreepartners);
                        //pushing optin details to db
                        $em->persist($newOptInDetails);
                        $em->flush($newOptInDetails);
                    } else {
                        //if user already exists under this resource
                        $newContact = new Contact();
                        $form2 = $this->createForm(ContactType::class, $newContact, [
                            'action' => $this -> generateUrl('index'),
                            'method' => 'POST'
                        ]);
                        return $this->render('FrontEnd/userexists.html.twig',[
                            'form2'=>$form2->createView(),
                            'name' => $newSubscriber->getFirstname(),
                            'lastname' => $newSubscriber->getLastname(),
                            'email' => $newSubscriber->getEmailAddress()
                        ]);
                    }
                }
                
                //create email
                $urlButton = $this->generateEmailUrl(($request->getLocale() === 'ru' ? '/ru/' : '/') . 'verify/' . $newSubscriber->getEmailAddress() . '?id=' . urlencode($hash));
                $message = Swift_Message::newInstance()
                    ->setSubject('OfficeJobsGuru.com | Complete Registration')
                    ->setFrom(['support@officejobsguru.com' => 'OfficeJobsGuru Support Team'])
                    ->setTo($newSubscriber->getEmailAddress())
                    ->setContentType("text/html")
                    ->setBody($this->renderView('FrontEnd/emailSubscribe.html.twig', [
                        'url' => $urlButton, 
                        'name' => $newSubscriber->getFirstname(),
                        'lastname' => $newSubscriber->getLastname(),
                        'email' => $newSubscriber->getEmailAddress()]));

                //send email
                $this->get('mailer')->send($message);

                //generating successfull responce page
                return $this->redirect($this->generateUrl('thankureg'));
                
            }
            
        } catch (Exception $ex) {
            $error = 1;
        } catch(DBALException $e) {
            $error = 1;
        }
        
        //CONTACT FORM
        $newContact = new Contact();
        $form2 = $this->createForm(ContactType::class, $newContact, [
            'action' => $this -> generateUrl('index'),
            'method' => 'POST'
        ]);
        
        $form2->handleRequest($request);
        $em = $this ->getDoctrine() ->getManager();
        if($form2->isValid() && $form2->isSubmitted()) {
            $name = $form2['name'] ->getData();
            $emailaddress = $form2['emailaddress'] ->getData();
            $subject = $form2['subject'] ->getData();
            $message = $form2['message'] ->getData();
            
            $query4 = $em ->createQuery('SELECT MAX(m.id) FROM AppBundle:Contact m');
            $newContact ->setId($query4->getSingleScalarResult() + 1);
            $newContact ->setName($name);
            $newContact ->setEmailAddress($emailaddress);
            $newContact ->setSubject($subject);
            $newContact ->setMessage($message);
            
            $em->persist($newContact);
            $em->flush();
            //create email

            $message = Swift_Message::newInstance()
                ->setSubject('OfficeJobsGuru.com | Question from Website |')
                ->setFrom($newContact->getEmailAddress())
                ->setTo('m@mediaff.com')
                ->setContentType("text/html")
                ->setBody($newContact->getMessage());

            //send email
            $this->get('mailer')->send($message);
            //generating successfull responce page
            return $this->redirect($this->generateUrl('index'));

         }
            
        return $this->render('FrontEnd/index.html.twig',[
            'form1'=>$form1->createView(),
            'form2'=>$form2->createView(),
            'error'=>$error
        ]);
    }
    
    /**
     * @Route("verify/{emailaddress}")
     * @Method("GET")
     */
    public function verifyEmailAction(Request $request, $emailaddress) {
        $newOptInDetails = new SubscriberOptInDetails();
        $subscriber = new SubscriberDetails();
        
        $em = $this->getDoctrine()->getManager('custom');
        $subscriber = $em->getRepository('AppBundle:SubscriberDetails') ->findOneBy(['emailaddress' => $emailaddress]);
        $userid = $subscriber ->getId();

        if(!$subscriber) {
            throw $this->createNotFoundException('U bettr go awai!');
        } else {
            $newOptInDetails = $em ->getRepository('AppBundle:SubscriberOptInDetails') ->findOneBy(['user' => $userid, 'resourceid' => 4]);
            $newOptInDetails ->setOptindate(new DateTime());
            $newOptInDetails ->setOptinip($_SERVER['REMOTE_ADDR']);
            $em->persist($newOptInDetails);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        } 
    }
    
    /**
    * @Route("thankureg", name="thankureg")
    */
    public function thankuregAction(Request $request)
    {
        $newContact = new Contact();
        $form2 = $this->createForm(ContactType::class, $newContact, [
            'action' => $this -> generateUrl('index'),
            'method' => 'POST'
        ]);
        return $this->render('FrontEnd/thankureg.html.twig', [
            'form2' => $form2 -> createView()
        ]);
    }
    
    /**
    * @Route("terms", name="terms")
    */
    public function termsAction(Request $request)
    {
        $newContact = new Contact();
        $form2 = $this->createForm(ContactType::class, $newContact, [
            'action' => $this -> generateUrl('index'),
            'method' => 'POST'
        ]);
        return $this->render('FrontEnd/terms.html.twig',[
            'form2' => $form2 -> CreateView()
        ]);
    }
    
    /**
    * @Route("privacy", name="privacy")
    */
    public function privacyAction(Request $request)
    {
        $newContact = new Contact();
        $form2 = $this->createForm(ContactType::class, $newContact, [
            'action' => $this ->generateUrl('index'),
            'method' => 'POST'
        ]);
        return $this->render('FrontEnd/privacy.html.twig', [
            'form2' => $form2 -> createView()
        ]);
    }
    
     /**
     * @Route("verify/unsubscribe/{emailaddress}")
     * @Method("GET")
     */
    public function verifyUnsubscribeAction(Request $request, $emailaddress) {
        $newOptOutDetails = new SubscriberOptOutDetails();
        $em = $this->getDoctrine()->getManager('custom');
        $subscriber = $em->getRepository('AppBundle:SubscriberDetails') ->findOneBy(['emailaddress' => $emailaddress]);
        
        if(!$subscriber) {
            throw $this->createNotFoundException('U bettr go awai!');
        } else {
            $query3 = $em ->createQuery('SELECT MAX(u.id) FROM AppBundle:SubscriberOptOutdetails u');
            $newOptOutDetails ->setId($query3->getSingleScalarResult() + 1);
            $newOptOutDetails ->setEmailAddress($emailaddress);
            $newOptOutDetails ->setUser($subscriber);
            $newOptOutDetails ->setResourceid(5);
            $newOptOutDetails ->setOptoutdate(new DateTime());
            $newOptOutDetails ->setOptoutip($_SERVER['REMOTE_ADDR']);
            $em->persist($newOptOutDetails);        
            $em->flush();
        }

        return $this->redirect($this->generateUrl('index'));
    }
    
    /**
    * @Route("unsubscribe", name="unsubscribe")
    */
    public function unsubscribeAction(Request $request) {   
        $error = 0;
        $unsubscriber = new SubscriberOptOutDetails();
        
        $form = $this->createForm(SubscriberOptOutType::class, $unsubscriber, [
            'action' => $this->generateUrl('unsubscribe'),
            'method' => 'POST']);
        
        $form->handleRequest($request);
        
        if($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager('custom');
            $subscriber = $em->getRepository('AppBundle:SubscriberDetails')->findOneByEmailaddress($unsubscriber->getEmailaddress());

            if($subscriber) {
                    $urlButton = $this->generateEmailUrl(($request->getLocale() === 'ru' ? '/ru/' : '/') . 'verify/unsubscribe/' . $subscriber->getEmailAddress() . '?id=' . urlencode($subscriber->getHash()));
                    $message = Swift_Message::newInstance()
                        ->setSubject('OfficeJobsGuru | We are sorry you are leaving us')
                        ->setFrom(['support@officejobsguru.com' => 'Jobbery Support Team'])
                        ->setTo($subscriber->getEmailAddress())
                        ->setContentType("text/html")
                        ->setBody($this->renderView('FrontEnd/emailUnsubscribe.html.twig', [
                            'url' => $urlButton, 
                            'name' => $subscriber->getFirstname(),
                            'lastname' => $subscriber->getLastname(),
                            'email' => $subscriber->getEmailAddress()
                            ]));

                    $this->get('mailer')->send($message);
                    return $this->redirect($this->generateUrl('sorryunsubscribe'));
            } else {
                $error = 1;
            }
        }
        
        $newContact = new Contact();
        $form2 = $this->createForm(ContactType::class, $newContact, [
            'action' => $this -> generateUrl('index'), 
            'method' => 'POST']);
        
        return $this->render('FrontEnd/unsubscribe.html.twig', [
            'form' => $form->createView(), 
            'form2' => $form2->createView(), 'error' => $error]);

    }    
    
    /**
    * @Route("sorryunsubscribe", name="sorryunsubscribe")
    */
    public function sorryunsubscribeAction(Request $request)
    {   
        $newContact = new Contact();
        $form2 = $this->createForm(ContactType::class, $newContact, [
            'action' => $this -> generateUrl('index'),
            'method' => 'POST']);
        return $this->render('FrontEnd/sorryunsubscribe.html.twig', [
            'form2' => $form2 -> CreateView()
        ]);
    }
    
        //controller specific functions
    
    private function generateKey($size) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = "";
        for($i = 0; $i < $size; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
     private function mc_encrypt($encrypt, $key) {
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $passcrypt = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, trim($encrypt), MCRYPT_MODE_ECB, $iv));
        $encode = base64_encode($passcrypt);
        return $encode;
    }
    
    private function generateEmailUrl($url) {
        return "http://officejobsguru.mediaff.com" . $this->container->get('router')->getContext()->getBaseUrl() . $url;
    }
    
}


