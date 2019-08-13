<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MenuItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Menu", inversedBy="menuItems")
     */
    private $menu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="menuItems")
     */
    private $page;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
