<?php
/**
 * @author Nicolai Maliske
 *
 * @link https://nicolai.maliske.net
 */

namespace App\Controller;


class Pagination
{
    /** @var int */
    private $currentPage;

    /** @var int */
    private $totalEntries;

    /** @var int */
    private $entriesPerPage;

    /** @var int */
    private $parts;

    public function getLimits()
    {
        $entriesPerPage = (empty($this->entriesPerPage))? 1 : $this->entriesPerPage;
        $totalEntries = (empty($this->totalEntries))? 1 : $this->totalEntries;

        $parts = ceil($totalEntries / $entriesPerPage);
        $this->parts = $parts;
        $currentPage = $this->currentPage($parts);

        $offset = 0;
        $limit = $entriesPerPage;

        if($currentPage === 1) {
            return ['limit' => $limit, 'offset' => $offset, 'maxPage' => $parts];
        }

        $offset = ($currentPage * $entriesPerPage) - $entriesPerPage;

        return ['limit' => $limit, 'offset' => $offset, 'maxPage' => $parts];
    }

    /**
     * @param int $nextPrevious
     * @return array
     */
    public function browse($nextPrevious = 3): array
    {
        $browsePage = [
            'prePages' => 0, 'preLinks' => [],
            'nextPages' => 0, 'nextLinks' => []
        ];

        $y = 0;
        for ($j = 1; $j <= $nextPrevious; $j++) {
            if (($this->currentPage - $j) <= 0) break;
            $browsePage['prePages'] = $j;
            $browsePage['preLinks'][$y] = $this->currentPage - $j;
            $y++;
        }

        $z = 0;
        for ($i = 1; $i <= $nextPrevious; $i++) {
            if($this->parts == $this->currentPage) break;
            $browsePage['nextPages'] = $i;
            $browsePage['nextLinks'][$z] = $this->currentPage + $i;
            $z++;
            if($this->parts <= ($this->currentPage + $i)) break;
        }

        asort($browsePage['preLinks']);
        return array_merge($browsePage, ['currentPage' => $this->currentPage]);
    }

    /**
     * @param int $parts
     * @return int
     */
    private function currentPage(int $parts): int
    {
        if(empty($this->currentPage)) {
            return 1;
        } elseif ($this->currentPage <= 0 || $this->currentPage > $parts) {
            return 1;
        }
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return $this
     */
    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @param int $totalEntries
     * @return $this
     */
    public function setTotalEntries(int $totalEntries): self
    {
        $this->totalEntries = $totalEntries;
        return $this;
    }

    /**
     * @param int $entriesPerPage
     * @return $this
     */
    public function setEntriesPerPage(int $entriesPerPage): self
    {
        $this->entriesPerPage = $entriesPerPage;
        return $this;
    }
}