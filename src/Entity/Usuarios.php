<?php

namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuariosRepository::class)]
class Usuarios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9)]
    private ?string $dni = null;

    #[ORM\Column(length: 25)]
    private ?string $nombre = null;

    #[ORM\Column(length: 60)]
    private ?string $apellidos = null;

    #[ORM\Column(length: 25)]
    private ?string $ciudad = null;

    #[ORM\Column(type: "boolean")]
    private ?bool $estado = true;

    #[ORM\Column(length: 50)]
    private ?string $direccion = null;

    #[ORM\Column(name: "fechaHora", type: "datetime")]
    private $fechaHora;

    public function __construct()
    {
        $this->fechaHora = new \DateTime();
    }

    public function getFechaHora(): ?\DateTimeInterface
    {
        return $this->fechaHora;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): static
    {
        $this->ciudad = $ciudad;

        return $this;
    }

     // Getter y setter para $estado
     public function getEstado(): ?bool
     {
         return $this->estado;
     }
 
     public function setEstado(?bool $estado): self
     {
         $this->estado = $estado;
 
         return $this;
     }

     public function getDireccion(): ?string
     {
         return $this->direccion;
     }

     public function setDireccion(string $direccion): static
     {
         $this->direccion = $direccion;

         return $this;
     }
}
