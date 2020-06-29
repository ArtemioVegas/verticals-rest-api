<?php

namespace App\DTO;

use App\Entity\Advert;
use App\Entity\Article;

final class AdvertAssembler
{
    /**
     * @param ArticleDTO $articleDTO
     * @return Article
     */
    public function readDTO(ArticleDTO $articleDTO): Advert
    {
        $article->setContent($articleDTO->getContent());
        $article->setTitle($articleDTO->getTitle());

        return $article;
    }

    /**
     * @param ArticleDTO $articleDTO
     * @return Article
     */
    public function createArticle(ArticleDTO $articleDTO): Article
    {
        return $this->readDTO($articleDTO);
    }
}
