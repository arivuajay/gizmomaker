<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/10/14
 * Time: 3:42 AM
 */

namespace Gizmo\GizmoBundle\Entity;


use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity()
 * @UniqueEntity(fields="email",groups={"registration"},message="Email address is already used")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \Serializable, \JsonSerializable
{

    public function jsonSerialize(){
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'avatar'=>$this->getAvatarWebPath()
        );
    }
    public function __construct()
    {
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));

        $this->Roles = new ArrayCollection();
        $this->Projects = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     *
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     *
     */
    private $phone;

    /**
     * @var string
     * @Assert\Email(groups={"registration"})
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(groups={"registration"},message="Password field is required")
     * @Assert\Length(min=7, groups={"registration"},minMessage="Password should have minimum 8 characters")
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

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
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="Users")
     *
     */
    private $Roles;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Project", mappedBy="User")
     */
    private $Projects;
    /**
     * @var string
     *
     * @ORM\Column(name="password_reset_token", type="string", length=255, unique=true, nullable=true)
     */
    private $passwordResetToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="token_generated_at", type="datetime",nullable=true)
     */
    private $tokenGeneratedAt;


    /**
     * @var string
     *
     * @ORM\Column(name="avatar_path", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @Assert\File(maxSize="1M")
     * @Assert\Image(minWidth = 200, mimeTypes = {
     *   "image/png",
     *   "image/pjpeg",
     *   "image/jpeg",
     *   "image/gif",
     *   "image/jpg"
     * })
     */
    private $file;

    private $temp;
    /**
     * @param string $passwordResetToken
     */
    public function setPasswordResetToken($passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
    }

    /**
     * @return string
     */
    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * @param \DateTime $tokenGeneratedAt
     */
    public function setTokenGeneratedAt($tokenGeneratedAt)
    {
        $this->tokenGeneratedAt = $tokenGeneratedAt;
    }

    /**
     * @return \DateTime
     */
    public function getTokenGeneratedAt()
    {
        return $this->tokenGeneratedAt;
    }


    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->Roles->toArray();
    }

    public function addRole($Role){
        $this->Roles->add($Role);
    }
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }


    //????? why?
    public function getUser(){
        return $this;
    }



    /**
     * @param string $path
     */
    public function setAvatarPath($path)
    {
        $this->avatar = $path;
    }

    /**
     * @return string
     */
    public function getAvatarPath()
    {
        return $this->avatar;
    }


    public function getAvatarAbsolutePath()
    {
        return null === $this->avatar
            ? null
            : $this->getUploadRootDir() . '/' . $this->avatar;
    }

    public function getAvatarWebPath()
    {
        return null === $this->avatar
            ? null
            : '/' . $this->getUploadDir() . '/' . $this->avatar;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return realpath('./' . $this->getUploadDir());
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'profile_pictures';
    }


    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->avatar)) {
            // store the old name to delete after the update
            $this->temp = $this->avatar;
            $this->avatar = null;
        } else {
            $this->avatar = '';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->avatar = $filename . '.' . $this->getFile()->guessExtension();

        }


    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->avatar);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAvatarAbsolutePath()) {
            unlink($file);
        }
    }

}
