<?php

namespace CodeChallenge\Domain\Model\Doctor;

class Doctor
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $error = false;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function markError(): void
    {
        $this->error = true;
    }

    public function clearError(): void
    {
        $this->error = false;
    }

    public function hasError(): bool
    {
        return $this->error;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}