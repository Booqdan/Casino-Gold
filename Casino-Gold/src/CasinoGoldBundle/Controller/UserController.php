<?php

namespace CasinoGoldBundle\Controller;

use CasinoGoldBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('CasinoGoldBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('CasinoGoldBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/show/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('CasinoGoldBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * wyswietlenie wybranego uzytkownika
     * @Route("/admin/user/{id}")
     */
    public function showOneAction(User $user, $id)
    {
        $deleteForm = $this->createDeleteForm($user);

        $users = $this
            ->getDoctrine()
            ->getRepository('CasinoGoldBundle:User')
            ->find($id);

        if(!$users){
            throw $this->createNotFoundException('User not exist');
        }
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, '...');

        return $this->render('user/show.html.twig', ['user' => $users,
            'delete_form' => $deleteForm->createView()]);
    }
    /**
     * wyswietlenie uzytkownikow przez admina
     * @Route("/admin/allusers")
     */
    public function showAllUsersAction()
    {
        $users = $this
            ->getDoctrine()
            ->getRepository('CasinoGoldBundle:User')
            ->findAll();

        if(!$users){
            throw $this->createNotFoundException('There is no users');
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('user/showall.html.twig', ['user' => $users]);
        }else{
            return $this->render('games/showgames.html.twig', ['user' => $users]);
        }
    }



    /**
     * wyswietlenie gier
     * @Route("/games")
     */
    public function gamesAction()
    {
        return $this->render('games/showgames.html.twig');
    }

    /**
     * akcja dobierania kart przez gracza
     * @Route("/games/blackjacknext", name="next")
     */
    public function blackjackNextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $cardSession = $session->get('cardsSession');

        $cards = $em->getRepository('CasinoGoldBundle:Card')->findAll();

        $randomKeyG = array_rand($cards, 1);
        $cardG = $cards[$randomKeyG];

        array_push($cardSession['gracz'], $cardG);

        $session->set('cardsSession', [
            'gracz' => $cardSession['gracz'],
            'krupier' => $cardSession['krupier'],
        ]);
        $cardSession = $session->get('cardsSession');
        $request->setSession($session);


        $scoregracz = $this->scoreGamer($cardSession['gracz']);
        $scoreKrupier = $this->scoreGamer($cardSession['krupier']);


        if($scoregracz <= 20){
            return $this->render('games/blackjacknext.html.twig', array(
                'cardsk' => $cardSession['krupier'],
                'cardsg' => $cardSession['gracz'],
                'cardnext' => $cardG,
                'session' => $cardSession,
                'scoregracz' => $scoregracz,
                'scorekrupier'=> $scoreKrupier,
            ));
        }else{
            return $this->render('games/blackjacktomuch.html.twig', array(
                'cardsk' => $cardSession['krupier'],
                'cardsg' => $cardSession['gracz'],
                'cardnext' => $cardG,
                'session' => $cardSession,
                'scoregracz' => $scoregracz,
                'scorekrupier'=> $scoreKrupier,
            ));
        }
    }

    /**
     * akcja rozpoczecia gry
     * @Route("/games/black", name="game")
     */

    public function blackjackGameAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cards = $em->getRepository('CasinoGoldBundle:Card')->findAll();

        $randomKeyK = array_rand($cards, 1);
        $cardK = $cards[$randomKeyK];

        unset($cards[$randomKeyK]);
        $randomKeyG = array_rand($cards, 1);
        $cardG = $cards[$randomKeyG];

        unset($cards[$randomKeyG]);
        $randomKeyG = array_rand($cards, 1);
        $cardSecondG = $cards[$randomKeyG];

        $krupierCards = [];
        $graczCards = [];

        array_push($krupierCards, $cardK);
        array_push($graczCards, $cardG);
        array_push($graczCards, $cardSecondG);

        $session = $this->get('session');
        $session->set('cardsSession', [
            'krupier' => $krupierCards,
            'gracz' => $graczCards,
        ]);

        $cardSession = $session->get('cardsSession');
        $request->setSession($session);

        $scoreGracz = $this->scoreGamer($graczCards);
        $scoreKrupier = $this->scoreGamer($krupierCards);

        if($scoreGracz <21){
            return $this->render('games/blackjackloader.html.twig', array(
                'cardk' => $cardSession['krupier'],
                'cardg' => $cardSession['gracz'],
                'scoregracz' => $scoreGracz,
                'scorekrupier' => $scoreKrupier,
            ));
        }else{
            return $this->render('games/blackjackBJWinner.html.twig', array(
                'cardk' => $cardSession['krupier'],
                'cardg' => $cardSession['gracz'],
                'scoregracz' => $scoreKrupier,
                'scorekrupier' => $scoreKrupier,

            ));
        }

    }
    /**
     * akcja dobierania kart przez krupiera
     * @Route("/games/blackjacknextkrupier", name="nextkrupier")
     */
    public function blackjackNextKrupierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $cardSession = $session->get('cardsSession');

        $cards = $em->getRepository('CasinoGoldBundle:Card')->findAll();

        $randomKeyK = array_rand($cards, 1);
        $cardK = $cards[$randomKeyK];

        array_push($cardSession['krupier'], $cardK);

        $session->set('cardsSession', [
            'gracz' => $cardSession['gracz'],
            'krupier' => $cardSession['krupier'],
        ]);
        $cardSession = $session->get('cardsSession');
        $request->setSession($session);


        $scoreKrupier = $this->scoreKrupier($cardSession['krupier']);
        $scoreGracz = $this->scoreGamer($cardSession['gracz']);

        if($scoreKrupier >21){
            return $this->render('games/blackjackkrupierloser.html.twig', array(
                'cardsk' => $cardSession['krupier'],
                'cardsg' => $cardSession['gracz'],
                'cardnext' => $cardK,
                'session' => $cardSession,
                'scorekrupier' => $scoreKrupier,
                'scoregracz' => $scoreGracz,
            ));
        }elseif ($scoreKrupier < 17){
            return $this->render('games/blackjacknext.html.twig', array(
                'cardsk' => $cardSession['krupier'],
                'cardsg' => $cardSession['gracz'],
                'cardnext' => $cardK,
                'session' => $cardSession,
                'scorekrupier' => $scoreKrupier,
                'scoregracz' => $scoreGracz,
            ));
        }elseif($scoreKrupier > 17 && $scoreKrupier > $scoreGracz){
            return $this->render('games/blackjackkrupierwins.html.twig', array(
                'cardsk' => $cardSession['krupier'],
                'cardsg' => $cardSession['gracz'],
                'cardnext' => $cardK,
                'session' => $cardSession,
                'scorekrupier' => $scoreKrupier,
                'scoregracz' => $scoreGracz,
            ));
        }elseif($scoreKrupier > 17 && $scoreKrupier < $scoreGracz){
            return $this->render('games/blackjackgamerwins.html.twig', array(
                'cardsk' => $cardSession['krupier'],
                'cardsg' => $cardSession['gracz'],
                'cardnext' => $cardK,
                'session' => $cardSession,
                'scorekrupier' => $scoreKrupier,
                'scoregracz' => $scoreGracz,
            ));
        }
    }


    private function scoreGamer($gamer)
    {
        $score = 0;
        foreach ($gamer as $key=>$value){
            $score += $value->getValue();
        }
        return $score;
    }

    private function scoreKrupier($krupier)
    {
        $score = 0;
        foreach ($krupier as $key=>$value){
            $score += $value->getValue();
        }
        return $score;
    }

}
