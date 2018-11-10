<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class TreeExtension extends AbstractExtension
{
     private $router;

     public function __construct(UrlGeneratorInterface $router)
     {
         $this->router = $router;
     }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getTreeComments', [$this, 'getTreeComments'],  ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param $comments
     * @param null $parent
     * @return string
     */
    public function getTreeComments($comments, $parent = null)
    {
        $tree = '<ul>';
        foreach ($comments as $comment) {
                if ($comment->getParent() == $parent) {
                    $answerLink = $this->getLinkAnswerComment($comment->getPersonality()->getId(), $comment->getId());
                    $tree .= '<li>'.$comment->getText().'
                                    <div>'.$answerLink.'
                                        <span style="float: right">дата</span>
                                    </div>
                               </li>';

                    $tree .= $this->getTreeComments($comments, $comment->getId());
            }
        }
        return  $tree.'</ul>';
    }

    public function getLinkAnswerComment($id, $commentId)
    {
        $link = $this->router->generate('personality_one', ['id' => $id, 'parent' => $commentId], UrlGeneratorInterface::ABSOLUTE_URL);
        $a = '<a href="' . $link . '">Ответить</a>';
        return $a;
    }

    public function doSomething($value)
    {
        // ...
    }
}
