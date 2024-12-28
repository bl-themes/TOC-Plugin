<?php

class TOCPlugin extends Plugin
{
    public function init()
    {
        $this->dbFields = array();
    }

   public function siteBodyBegin()
{
    global $WHERE_AM_I, $page;

    if ($WHERE_AM_I == 'page') {
        // Retrieve the original content of the page
        $content = $page->content();

        // Check if the TOC is already present in the content.
        if (strpos($content, '<div id="toc">') === false) {
            // Generate TOC based on content
            $toc = $this->generateTOC($content);

           // If the TOC is successfully created, add the TOC at the beginning of the content.
            if (!empty($toc)) {
                // Save only new content (TOC + article with link ID)
                $modifiedContent = $toc; 
                $page->setField('content', $modifiedContent);
            }
        } else {
            // If the TOC already exists, use the content as is without modification.
            $page->setField('content', $content);
        }
    }
}


    // Function to generate TOC
    public function generateTOC($content)
    {
        $toc = '<div id="toc"><h5 class="mb-3 pb-3 border-bottom">Daftar Isi</h5><ul>';

       // Regex to find headings from h2 to h6
        $pattern = '/<h([2-3])([^>]*)>(.*?)<\/h\1>/i';

        // Matching headings in the content
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        // If there is no heading, return empty.
        if (empty($matches)) {
            return ''; // No heading, TOC will not be displayed.
        }

        // Process each heading found and add an anchor ID.
        foreach ($matches as $match) {
            // Creating anchor IDs based on heading text
            $headingText = strip_tags($match[3]);
            $anchorID = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $headingText), '-'));

            // Adding anchor ID to heading
            $content = str_replace($match[0], '<h' . $match[1] . ' id="' . $anchorID . '">' . $match[3] . '</h' . $match[1] . '>', $content);

            // Adding items to TOC
            $toc .= '<li class="toc-level-' . $match[1] . '"><a href="#' . $anchorID . '">' . $headingText . '</a></li>';
        }

        // Closing the tags <ul> and <div>
        $toc .= '</ul></div>';

        // Return the TOC and article content
        return $toc . $content;
    }
}
