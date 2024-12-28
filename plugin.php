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
        // Ambil konten asli halaman
        $content = $page->content();

        // Periksa apakah TOC sudah ada di konten
        if (strpos($content, '<div id="toc">') === false) {
            // Generate TOC berdasarkan konten
            $toc = $this->generateTOC($content);

            // Jika TOC berhasil dibuat, tambahkan TOC ke awal konten
            if (!empty($toc)) {
                // Simpan hanya konten baru (TOC + artikel dengan ID link)
                $modifiedContent = $toc; // Hanya gunakan TOC dengan link
                $page->setField('content', $modifiedContent);
            }
        } else {
            // Jika TOC sudah ada, gunakan konten apa adanya tanpa modifikasi
            $page->setField('content', $content);
        }
    }
}


    // Fungsi untuk menghasilkan TOC
    public function generateTOC($content)
    {
        $toc = '<div id="toc"><h5 class="mb-3 pb-3 border-bottom">Daftar Isi</h5><ul>';

        // Regex untuk mencari heading h2 sampai h6
        $pattern = '/<h([2-3])([^>]*)>(.*?)<\/h\1>/i';

        // Mencocokkan heading dalam konten
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        // Jika tidak ada heading, return kosong
        if (empty($matches)) {
            return ''; // Tidak ada heading, TOC tidak akan ditampilkan
        }

        // Proses setiap heading yang ditemukan dan tambahkan ID anchor
        foreach ($matches as $match) {
            // Membuat ID anchor berdasarkan teks heading
            $headingText = strip_tags($match[3]);
            $anchorID = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $headingText), '-'));

            // Menambahkan ID anchor ke heading
            $content = str_replace($match[0], '<h' . $match[1] . ' id="' . $anchorID . '">' . $match[3] . '</h' . $match[1] . '>', $content);

            // Menambahkan item ke TOC
            $toc .= '<li class="toc-level-' . $match[1] . '"><a href="#' . $anchorID . '">' . $headingText . '</a></li>';
        }

        // Menutup tag <ul> dan <div>
        $toc .= '</ul></div>';

        // Kembalikan TOC dan konten artikel
        return $toc . $content;
    }
}
