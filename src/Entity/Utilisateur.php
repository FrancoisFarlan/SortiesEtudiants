<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un utilisateur avec cette adresse email")
 * @UniqueEntity(fields={"pseudo"}, message="Il existe déjà un utilisateur avec ce pseudo")
 */
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message="L'adresse email indiquée n'est pas valide")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="Merci de renseigner votre nom")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="Merci de renseigner votre prénom")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="Merci de renseigner votre numéro de téléphone")
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participant")
     */
    private $sorties;



    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="Organisateur", orphanRemoval=true)
     *
     */
    private $sortieOrganisee;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortieOrganisee = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
//        $roles = $this->roles;
//        $roles[] = 'ROLE_USER';
//        return array_unique($roles);

//        la ligne suivante permet de gerer les differents roles presents dans la bdd
        return $this->roles;

    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->addParticipant($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeParticipant($this);
        }

        return $this;
    }


    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortieOrganisee(): Collection
    {
        return $this->sortieOrganisee;
    }

    public function addSortieOrganisee(Sortie $sortieOrganisee): self
    {
        if (!$this->sortieOrganisee->contains($sortieOrganisee)) {
            $this->sortieOrganisee[] = $sortieOrganisee;
            $sortieOrganisee->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortieOrganisee(Sortie $sortieOrganisee): self
    {
        if ($this->sortieOrganisee->removeElement($sortieOrganisee)) {
            // set the owning side to null (unless already changed)
            if ($sortieOrganisee->getOrganisateur() === $this) {
                $sortieOrganisee->setOrganisateur(null);
            }
        }

        return $this;
    }
}
