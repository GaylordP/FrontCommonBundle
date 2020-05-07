<?php

namespace GaylordP\FrontCommonBundle\Form\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name
     * 
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set email
     * 
     * @param string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = strtolower($email);
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set message
     * 
     * @param string $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
