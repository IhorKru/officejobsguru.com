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
use AppBundle\Form\SubscriberOptInType;
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
                $gender = $form1['gender']->getData();
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
                
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('AppBundle:SubscriberDetails') ->findOneBy(['emailaddress' => $emailaddress]);
                
                if(!$entity) {
                    $newSubscriber ->setFirstname($firstname);
                    $newSubscriber ->setLastname($lastname);
                    $newSubscriber ->setEmailaddress($emailaddress);
                    $newSubscriber ->setPhone($phone);
                    $newSubscriber ->setAge(-1);
                    $newSubscriber ->setGender($gender);
                    $newSubscriber ->setEducationLevelId(-1);
                    $newSubscriber ->setHash($hash);
                    
                    $newOptInDetails ->setUser($newSubscriber);
                    $newOptInDetails ->setResourceid(1);
                    $newOptInDetails ->setAgreeterms($agreeterms);
                    $newOptInDetails ->setAgreeemails($agreeemails);
                    $newOptInDetails ->setAgreepartners($agreepartners);
                    
                    //pusshing data through to the database
                    $em->persist($newSubscriber);
                    $em->persist($newOptInDetails);
                    $em->flush();
                    
                } else {
                    
                    $newOptInDetails ->setUser($entity);
                    $newOptInDetails ->setResourceid(1);
                    $newOptInDetails ->setAgreeterms($agreeterms);
                    $newOptInDetails ->setAgreeemails($agreeemails);
                    $newOptInDetails ->setAgreepartners($agreepartners);

                    //pushing to database
                    $em->persist($newOptInDetails);
                    $em->flush($newOptInDetails);
                }
                
                //create email
                $urlButton = $this->generateEmailUrl(($request->getLocale() === 'ru' ? '/ru/' : '/') . 'verify/' . $newSubscriber->getEmailAddress() . '?id=' . urlencode($hash));
                $message = Swift_Message::newInstance()
                    ->setSubject('OfficeJobsGuru.com | Complete Registration')
                    ->setFrom(array('relaxstcom@gmail.com' => 'OfficeJobsGuru Support Team'))
                    ->setTo($newSubscriber->getEmailAddress())
                    ->setContentType("text/html")
                    ->setBody($this->renderView('FrontEnd/emailSubscribe.html.twig', array(
                            'url' => $urlButton, 
                            'name' => $newSubscriber->getFirstname(),
                            'lastname' => $newSubscriber->getLastname(),
                            'email' => $newSubscriber->getEmailAddress()
                        )));

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

        if($form2->isValid() && $form2->isSubmitted()) {
            $name = $form2['name'] ->getData();
            $emailaddress = $form2['emailaddress'] ->getData();
            $subject = $form2['subject'] ->getData();
            $message = $form2['message'] ->getData();

            $newContact ->setName($name);
            $newContact ->setEmailAddress($emailaddress);
            $newContact ->setSubject($subject);
            $newContact ->setMessage($message);

            //create email

            $message = Swift_Message::newInstance()
                ->setSubject('OfficeJobsGuru.com | Question from Website |')
                ->setFrom($newContact->getEmailAddress())
                ->setTo('kruchynenko@gmail.com')
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
        $em = $this->getDoctrine()->getManager();
        $subscriber = $em->getRepository('AppBundle:Subscriber')->findOneByEmailaddress($emailaddress);

        if(!$subscriber) {
            throw $this->createNotFoundException('U bettr go awai!');
        }

        $equals = (strcmp($subscriber->getHash(), $request->get("id", "")) === 0 && strcmp($subscriber->getEmailAddress(), $emailaddress) === 0);
        if($equals) {
            $subscriber->setSubscriptionDate(new DateTime());
            $subscriber->setSubscriptionIp($_SERVER['REMOTE_ADDR']);
            $em->persist($subscriber);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->redirect($this->generateUrl('index'));
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
        $em = $this->getDoctrine()->getManager();
        $subscriber = $em->getRepository('AppBundle:Subscriber')->findOneByEmailaddress($emailaddress);

        if(!$subscriber) {
            throw $this->createNotFoundException('U bettr go awai!');
        }

        $equals = (strcmp($subscriber->getHash(), $request->get("id", "")) === 0 && strcmp($subscriber->getEmailaddress(), $emailaddress) === 0);
        if($equals) {
            $subscriber->setUnsubscriptionDate(new \DateTime());
            $subscriber->setUnsubscriptionIp($_SERVER['REMOTE_ADDR']);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('index'));
    }
    
    /**
    * @Route("unsubscribe", name="unsubscribe")
    */
    public function unsubscribeAction(Request $request) {   
        $error = 0;
        $unsubscriber = new Unsubscriber();
        
        $form = $this->createForm(UnsubscriberType::class, $unsubscriber, array(
            'action' => $this->generateUrl('unsubscribe'),
            'method' => 'POST'
        ));
        
        $form->handleRequest($request);
        
        $newContact = new Contact();
        $form2 = $this->createForm(ContactType::class, $newContact, array(
            'action' => $this -> generateUrl('index'),
            'method' => 'POST'
            ));
        
        if($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $subscriber = $em->getRepository('AppBundle:Subscriber')->findOneByEmailaddress($unsubscriber->getEmailAddress());

            if($subscriber) {
                    $urlButton = $this->generateEmailUrl(($request->getLocale() === 'ru' ? '/ru/' : '/') . 'verify/unsubscribe/' . $subscriber->getEmailAddress() . '?id=' . urlencode($subscriber->getHash()));
                    $message = Swift_Message::newInstance()
                        ->setSubject('FinSensitive | We are sorry you are leaving us')
                        ->setFrom(array('relaxstcom@gmail.com' => 'FinSensitive Support Team'))
                        ->setTo($subscriber->getEmailAddress())
                        ->setContentType("text/html")
                        ->setBody($this->renderView('FrontEnd/emailUnsubscribe.html.twig',[
                            'name'=> $subscriber->getFirstname(),
                            'lastname'=>$subscriber->getLastname(),
                            'email'=> $subscriber->getEmailAddress(),
                            'url' => $urlButton
                        ]));

                    $this->get('mailer')->send($message);
                    return $this->redirect($this->generateUrl('sorryunsubscribe'));
            } else {
                $error = 1;
            }
        }

        return $this->render('FrontEnd/unsubscribe.html.twig', array(
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'error' => $error
        ));

    } 
    
    /**
    * @Route("sorryunsubscribe", name="sorryunsubscribe")
    */
    public function sorryunsubscribeAction(Request $request)
    {   
        $newContact = new Contact();
        $form2 = $this->createForm(ContactType::class, $newContact, array(
            'action' => $this -> generateUrl('index'),
            'method' => 'POST'
            ));
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
        return "http://localhost:8888" . $this->container->get('router')->getContext()->getBaseUrl() . $url;
    }
    
}


