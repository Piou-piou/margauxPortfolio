<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Project
 *
 * @ORM\Table(name="ribsmodule_portfoliomargaux_article")
 * @ORM\Entity
 */
class Project
{
	const PUBLISHED = 1,
		DRAFT = 2,
		ARCHIVED = 3;
	
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="title_tag", type="string", length=150, nullable=false)
	 */
	private $titleTag;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="description_tag", type="string", length=150, nullable=false)
	 */
	private $descriptionTag;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="title", type="string", length=100, nullable=false)
	 */
	private $title;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="string", length=150, nullable=false)
	 */
	private $description;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="article", type="text", nullable=true)
	 */
	private $article;
	
	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="url", type="string", length=100, nullable=true)
	 */
	private $url;
	
	/**
	 * @var \DateTime|null
	 *
	 * @ORM\Column(name="publication_date", type="datetime", nullable=true)
	 */
	private $publicationDate;
	
	/**
	 * @var int
	 *
	 * @ORM\Column(name="state", type="integer", nullable=false)
	 */
	private $state = self::PUBLISHED;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="images_dir", type="string", nullable=true)
	 */
	private $imagesDir;
	
	/**
	 * @var \DateTime
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(name="creation_date", type="date", nullable=true)
	 */
	private $creationDate;
	
	/**
	 * @var \DateTime
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(name="update_date", type="date", nullable=true)
	 */
	private $updateDate;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
	}
	
	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}
	
	/**
	 * @param int $id
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}
	
	/**
	 * @return string
	 */
	public function getTitleTag(): ?string
	{
		return $this->titleTag;
	}
	
	/**
	 * @param string $titleTag
	 */
	public function setTitleTag(string $titleTag)
	{
		$this->titleTag = $titleTag;
	}
	
	/**
	 * @return string
	 */
	public function getDescriptionTag(): ?string
	{
		return $this->descriptionTag;
	}
	
	/**
	 * @param string $descriptionTag
	 */
	public function setDescriptionTag(string $descriptionTag)
	{
		$this->descriptionTag = $descriptionTag;
	}
	
	/**
	 * @return string
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}
	
	/**
	 * @param string $title
	 */
	public function setTitle(string $title)
	{
		$this->title = $title;
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): ?string
	{
		return $this->description;
	}
	
	/**
	 * @param string $description
	 */
	public function setDescription(string $description): void
	{
		$this->description = $description;
	}
	
	/**
	 * @return string
	 */
	public function getArticle(): ?string
	{
		return $this->article;
	}
	
	/**
	 * @param string $article
	 */
	public function setArticle(?string $article)
	{
		$this->article = $article;
	}
	
	/**
	 * @return null|string
	 */
	public function getUrl()
	{
		return $this->url;
	}
	
	/**
	 * @param null|string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}
	
	/**
	 * @return \DateTime|null
	 */
	public function getPublicationDate()
	{
		return $this->publicationDate;
	}
	
	/**
	 * @param \DateTime|null $publicationDate
	 */
	public function setPublicationDate($publicationDate)
	{
		$this->publicationDate = $publicationDate;
	}
	
	/**
	 * @return string
	 */
	public function getState(): string
	{
		if ($this->state === $this::DRAFT) {
			return "DRAFT";
		} else if ($this->state === $this::ARCHIVED) {
			return "ARCHIVED";
		}
		
		return "PUBLISHED";
	}
	
	/**
	 * @param int $state
	 */
	public function setState(int $state)
	{
		$this->state = $state;
	}
	
	/**
	 * @return string
	 */
	public function getImagesDir(): ?string
	{
		return $this->imagesDir;
	}
	
	/**
	 * @param string $images
	 */
	public function setImagesDir(string $images): void
	{
		$this->imagesDir = $images;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getCreationDate(): \DateTime
	{
		return $this->creationDate;
	}
	
	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate(\DateTime $creationDate)
	{
		$this->creationDate = $creationDate;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getUpdateDate(): \DateTime
	{
		return $this->updateDate;
	}
	
	/**
	 * @param \DateTime $updateDate
	 */
	public function setUpdateDate(\DateTime $updateDate)
	{
		$this->updateDate = $updateDate;
	}
}
