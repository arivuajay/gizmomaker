<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 12/13/14
 * Time: 3:46 PM
 */

namespace Gizmo\GizmoBundle\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Templating\EngineInterface;

class ContactController {

    public function __construct(EngineInterface $templating, Router $router, \Swift_Mailer $mailer){
        $this->templating = $templating;
        $this->router = $router;
        $this->mailer  = $mailer;
    }

    public function contactAction(Request $request){

        $message = \Swift_Message::newInstance()
            ->setSubject('Contact infomation')
            ->setFrom('notifications@gizmomaker.co.il')
            ->setTo(array('nimrod.rotem@gmail.com','asanka@ninearts.com'))
            ->setBody(
                $this->templating->render(
                    'GizmoBundle:Emails:contact_form.html.twig',
                    array(
                        'name'=>$request->get('name'),
                        'email'=>$request->get('email'),
                        'phone'=>$request->get('phone'),
                        'message'=>$request->get('message')
                    )
                ),'text/html'
            )
        ;
        $this->mailer->send($message);
        return new RedirectResponse($request->headers->get('referer'));
    }
}