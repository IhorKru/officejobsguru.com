<?php

namespace AppBundle\Controller;

use DateTime;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Subscriber;
use AppBundle\Entity\Unsubscriber;
use AppBundle\Entity\Contact;
use AppBundle\Form\SubscriberType;
use AppBundle\Form\UnsubscriberType;
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
            $newSubscriber = new Subscriber();
            
            $form1 = $this->createForm(SubscriberType::class, $newSubscriber, [
                'action' => $this -> generateUrl('index'),
                'method' => 'POST'
                ]);
            
            $form1->handleRequest($request);
            
            if($form1->isValid() && $form1->isSubmitted()) {
                $firstname = $form1['firstname']->getData();
                $lastname = $form1['lastname']->getData();
                $emailaddress = $form1['emailaddress']->getData();
                $phone = $form1['phone']->getData();
                $age = $form1['age']->getData();
                $agreeterms = $form1['agreeterms']->getData();
                $agreeemails = $form1['agreeemails']->getData();
                $agreepartners = $form1['agreepartners']->getData();
                
                $hash = $this->mc_encrypt($newSubscriber->getEmailAddress(), $this->generateKey(16));
                
                $em = $this->getDoctrine()->getManager();
                
                //assigning data to variables
                $newSubscriber ->setFirstname($firstname);
                $newSubscriber ->setLastname($lastname);
                $newSubscriber ->setEmailAddress($emailaddress);
                $newSubscriber ->setPhone($phone);
                $newSubscriber ->setAge($age);
                $newSubscriber ->setGender(-1);
                $newSubscriber ->setEducationLevelId(-1);
                $newSubscriber ->setResourceId(3);
                $newSubscriber ->setAgreeTerms($agreeterms);
                $newSubscriber ->setAgreeEmails($agreeemails);
                $newSubscriber ->setAgreePartners($agreepartners);
                $newSubscriber ->setHash($hash);
                
                //pusshing data through to the database
                $em->persist($newSubscriber);
                $em->flush();
                
                //create email
                $urlButton = $this->generateEmailUrl(($request->getLocale() === 'ru' ? '/ru/' : '/') . 'verify/' . $newSubscriber->getEmailAddress() . '?id=' . urlencode($hash));
                $message = Swift_Message::newInstance()
                    ->setSubject('FinSensitive.com | Complete Registration')
                    ->setFrom(array('relaxstcom@gmail.com' => 'FinSensitive Support Team'))
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
                ->setSubject('FinSensitive.com | Question from Website |')
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
    * @Route("/thankureg", name="thankureg")
    */
    public function thankuregAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/thankureg.html.twig');
    }
    
    /**
    * @Route("/terms", name="terms")
    */
    public function termsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/terms.html.twig');
    }
    
    /**
    * @Route("/privacy", name="privacy")
    */
    public function privacyAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/privacy.html.twig');
    }
    
    /**
    * @Route("/unsubscribe", name="unsubscribe")
    */
    public function unsubscribeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/unsubscribe.html.twig');
    }
    
    /**
    * @Route("/sorryunsubscribe", name="sorryunsubscribe")
    */
    public function sorryunsubscribeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/sorryunsubscribe.html.twig');
    }
    
}


