<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/10/14
 * Time: 3:31 AM
 */

namespace Gizmo\GizmoBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Gizmo\GizmoBundle\Entity\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project implements \JsonSerializable
{


    public function jsonSerialize()
    {

        $slides = [];
        foreach ($this->getSlides() as $slide) {
            $slides[] = array(
                'id' => $slide->getId(),
                'type' => $slide->getType(),
                'value' => $slide->getValue(),
                'webPath' => $slide->getWebPath()
            );
        }
        return array(
            'name' => $this->name,
            'name2' => $this->name2,
            'shortDescription' => $this->shortDescription,
            'fullDescription' => $this->fullDescription,
            'title' => $this->title,
//            'pageTitle' => $this->pageTitle,
            'pageTitle' => $this->name2,
            'isPublished' => $this->isPublished,
            'likeCnt' => $this->likeCnt,
            'dislikeCnt' => $this->dislikeCnt,
            'code' => $this->code,
            'created' => $this->createdAt->format('Y-m-d'),
            'updated' => $this->updatedAt->format('Y-m-d'),
            'user' => $this->getUser(),
            'slides' => $slides
        );

    }

    public function __construct()
    {
        $this->Slides = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name",type="string")
     */
    private $name;

    /**
     * @ORM\Column(name="name2",type="string")
     */
    private $name2;


    /**
     * @ORM\Column(name="title",type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(name="page_title",type="string", nullable=true)
     */
    private $pageTitle;


    /**
     * @ORM\Column(name="short_description",type="text", length=65535, nullable=true)
     */
    private $shortDescription;

    /**
     * @ORM\Column(name="full_description",type="text", nullable=true)
     */
    private $fullDescription;

    /**
     * @ORM\Column(name="is_published", type="smallint", length=1)
     */
    private $isPublished = 0;

    /**
     * @ORM\Column(name="like_cnt", type="integer", length=11)
     */
    private $likeCnt;
    /**
     * @ORM\Column(name="dislike_cnt", type="integer", length=11)
     */
    private $dislikeCnt;

    protected $ratioLikes;


    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $User;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="code",type="string", unique=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="ProjectSlide", fetch="EAGER", mappedBy="Project",cascade={"persist", "remove"})
     */
    private $Slides;

    /**
     * @return mixed
     */
    public function getSlides()
    {
        return $this->Slides;
    }

    /**
     * @param mixed $Slides
     */
    public function setSlides($Slides)
    {
        $this->Slides = $Slides;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param mixed $User
     */
    public function setUser($User)
    {
        $this->User = $User;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getFullDescription()
    {
        return $this->fullDescription;
    }

    /**
     * @param mixed $fullDescription
     */
    public function setFullDescription($fullDescription)
    {
        $this->fullDescription = $fullDescription;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param mixed $isPublished
     */
    public function setIsPublished($isPublished)
    {

        $this->isPublished = $isPublished;
    }

    public function getLikeCnt()
    {
        return $this->likeCnt;
    }

    public function setLikeCnt($likeCnt)
    {
        $this->likeCnt = $likeCnt;
    }
    public function getDislikeCnt()
    {
        return $this->dislikeCnt;
    }

    public function setDislikeCnt($dislikeCnt)
    {
        $this->dislikeCnt = $dislikeCnt;
    }

    public function getRatioLikes()
    {
        $totalCnt = $this->likeCnt + $this->dislikeCnt;
        return ($this->likeCnt / $totalCnt) * 100;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName2()
    {
        return $this->name2;
    }

    /**
     * @param mixed $name2
     */
    public function setName2($name2)
    {
        $this->name2 = $name2;
    }

    /**
     * @return mixed
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * @param mixed $pageTitle
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @return mixed
     */
    public function getShortWordsDescription($limit = 20)
    {
        return implode(' ', array_slice(explode(' ', $this->fullDescription), 0, $limit));
        return ;
    }

    /**
     * @param mixed $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        if (!$createdAt instanceof \DateTime) {
            $createdAt = new \DateTime($createdAt);
        }

        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function hasSlidePhoto()
    {
        $has_photo = false;

        foreach ($this->getSlides() as $Slide) {
            if ($Slide->getType() == 'photo') {
                $has_photo = true;
                break;
            }
        }
        return $has_photo;
    }

    public function getRandomSlidePhoto()
    {

        $slides = $this->Slides->filter(function ($slide) {
            return $slide->getType() == 'photo';
        });
        if ($slides && !empty($slides)) {
            $keys = $slides->getKeys();
            shuffle($keys);
            $random_index = $keys[0];
            return $slides->get($random_index);

        }
        return null;
    }
}