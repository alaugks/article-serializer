<?php declare(strict_types=1);

namespace App\Dto;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class ContactDto
{
    public const SALUTATION  = ['1' => 'MALE', '2' => 'FEMALE', '3' => 'DIVERS'];

    public const MARKETING_INFORMATION  = ['1' => true, '2' => false];

    #[SerializedName('1')]
    #[Type('string')]
    private ?string $firstname = null;

    #[SerializedName('2')]
    #[Type('string')]
    private ?string $lastname = null;

    #[SerializedName('3')]
    #[Type('string')]
    private ?string $email = null;

    #[Type("DateTime<'Y-m-d'>")]
    #[SerializedName('4')]
    private ?\DateTimeInterface $birthdate = null;

    // Example as JSON:
    // #[Type("MappingTable<'{\"1\": \"MALE\", \"2\": \"FEMALE\", \"6\": \"DIVERS\"}'>")]
    // Example with constant:
    #[Type("MappingTable<'App\Dto\ContactDto::SALUTATION'>")]
    #[SerializedName('46')]
    private ?string $salutation = null;

    // Example as JSON:
    // #[Type("MappingTable<'{\"1\": true, \"2\": false}'>")]
    // Example with constant:
    #[Type("MappingTable<'App\Dto\ContactDto::MARKETING_INFORMATION'>")]
    #[SerializedName('100674')]
    private ?bool $marketingInformation = null;

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getSalutation(): ?string
    {
        return $this->salutation;
    }

    public function setSalutation(?string $salutation): void
    {
        $this->salutation = $salutation;
    }

    public function isMarketingInformation(): ?bool
    {
        return $this->marketingInformation;
    }

    public function setMarketingInformation(?bool $marketingInformation): void
    {
        $this->marketingInformation = $marketingInformation;
    }
}
