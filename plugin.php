<?php

class TOCPlugin extends Plugin
{
    public function init()
    {
        // Adding a hook to insert TOC
        $this->dbFields = array();
    }

    public function siteBodyBegin()
    {
        global $WHERE_AM_I, $page;

        if ($WHERE_AM_I == 'page') {
            // Take the page content
            $content = $page->content();

            // Generating TOC
            $toc = $this->generateTOC($content);

            // If the TOC is not empty (which means there are headings), add the TOC at the beginning of the article content.
            if (!empty($toc)) {
                $page->setField('content', $toc . $content);
            } else {
                // If there is no TOC, only display content without TOC.
                $page->setField('content', $content);
            }
        }
    }

    // Function to generate TOC
    public function generateTOC($content)
    {
        // This is an example of processing for TOC
        $toc = '<div id="toc"><h5 class="mb-3 pb-3 border-bottom">Daftar Isi</h5><ul>';
        
        // Regex to match headings h2 to h6.
        $pattern = '/<h([2-3])([^>]*)>(.*?)<\/h\1>/i';
        
        // Matching the headings present in the content.
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        // If no heading is found, return empty so that the TOC is not displayed.
        if (empty($matches)) {
            return ''; // There is no heading, and the table of contents is not displayed.
        }

        // Process each heading found and add an anchor ID.
        foreach ($matches as $match) {
            // Membuat ID anchor berdasarkan teks heading
            $headingText = strip_tags($match[3]);
            $anchorID = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $headingText), '-'));

            // Adding an anchor ID to a heading
            $content = str_replace($match[0], '<h' . $match[1] . ' id="' . $anchorID . '">' . $match[3] . '</h' . $match[1] . '>', $content);

            // Adding items to TOC
            $toc .= '<li class="toc-level-' . $match[1] . '"><a href="#' . $anchorID . '">' . $headingText . '</a></li>';
        }

        // Closing tag <ul> and <div>
        $toc .= '</ul></div>';

        // Restore TOC and article content
        return $toc . $content;
    }
}
