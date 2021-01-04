<?php
    // Adapted from Joseba Bikandi @ biophp.org:

    // author    Joseba Bikandi
    // license   GNU GPL v2
    // source code available at  biophp.org



    function remove_non_coding($seq) {
        // change the sequence to upper case
        $seq=strtoupper($seq);
        // remove non-words (\W), con coding ([^ATGCYRWSKMDVHBN]) and digits (\d) from sequence
        $seq=preg_replace("/\W|[^ATGCYRWSKMDVHBN]|\d/","",$seq);
        // replace all X by N (to normalized sequences)
        $seq=preg_replace("/X/","N",$seq);
        return $seq;
    }

    function Reverse($seq) {
        return strrev($seq);
    }

    function Complement($seq) {
        // Can be wholly replaced with strtr()
        // NOTE: why are W and S unaffected? is that a typo in the original code?
        //return strtr($seq, 'ATGCYRWSKMDHVBatgcyrwskmdhvb', 'TACGRYWSMKHDBVtacgrywsmkhdbv');
        
        // change the sequence to upper case
        $seq = strtoupper ($seq);
        // the system used to get the complementary sequence is simple but fas
        $seq=str_replace("A", "t", $seq);
        $seq=str_replace("T", "a", $seq);
        $seq=str_replace("G", "c", $seq);
        $seq=str_replace("C", "g", $seq);
        
        $seq=str_replace("Y", "r", $seq);
        $seq=str_replace("R", "y", $seq);
        $seq=str_replace("W", "w", $seq);
        $seq=str_replace("S", "s", $seq);
        $seq=str_replace("K", "m", $seq);
        $seq=str_replace("M", "k", $seq);
        $seq=str_replace("D", "h", $seq);
        $seq=str_replace("V", "b", $seq);
        $seq=str_replace("H", "d", $seq);
        $seq=str_replace("B", "v", $seq);
        // change the sequence to upper case again for output
        $seq = strtoupper ($seq);
        return $seq;
    }

    function DNAtoRNA($seq) {
        //eq = strtoupper($seq);
        // replace T by U
        $seq=preg_replace("/T/","U",$seq);
        $seq=preg_replace("/t/","u",$seq);
        return $seq;
    }

    function DNAtoProtein($seq,$genetic_code=1) {
        $seq = strtoupper($seq);
        // $aminoacids is the array of aminoacids
        $aminoacids=array("F","L","I","M","V","S","P","T","A","Y","*","H","Q","N","K","D","E","C","W","R","G","X");

        // $triplets is the array containning the genetic codes
        // Info has been extracted from http://www.ncbi.nlm.nih.gov/Taxonomy/Utils/wprintgc.cgi?mode
        
        // Standard genetic code
        $triplets[1]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG |TGA )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Vertebrate Mitochondrial
        $triplets[2]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG |AGA |AGG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG |TGA )","(CG. )","(GG. )","(\S\S\S )");
        // Yeast Mitochondrial
        $triplets[3]=array("(TTT |TTC )","(TTA |TTG )","(ATT |ATC )","(ATG |ATA )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. |CT. )","(GC. )","(TAT |TAC )","(TAA |TAG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG |TGA )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Mold, Protozoan and Coelenterate Mitochondrial. Mycoplasma, Spiroplasma
        $triplets[4]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG |TGA )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Invertebrate Mitochondrial
        $triplets[5]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC )","(ATG |ATA )","(GT. )","(TC. |AG. )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG |TGA )","(CG. )","(GG. )","(\S\S\S )");
        // Ciliate Nuclear; Dasycladacean Nuclear; Hexamita Nuclear
        $triplets[6]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TGA )","(CAT |CAC )",
                        "(CAA |CAG |TAA |TAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Echinoderm Mitochondrial
        $triplets[9]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AG. )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAA |AAT |AAC )","(AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG |TGA )","(CG. )","(GG. )","(\S\S\S )");
        // Euplotid Nuclear
        $triplets[10]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC |TGA )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Bacterial and Plant Plastid
        $triplets[11]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG |TGA )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Alternative Yeast Nuclear
        $triplets[12]=array("(TTT |TTC )","(TTA |TTG |CTA |CTT |CTC )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC |CTG )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG |TGA )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Ascidian Mitochondrial
        $triplets[13]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC )","(ATG |ATA )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG |TGA )","(CG. )","(GG. |AGA |AGG )","(\S\S\S )");
        // Flatworm Mitochondrial
        $triplets[14]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AG. )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC |TAA )","(TAG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC |AAA )","(AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG |TGA )","(CG. )","(GG. )","(\S\S\S )");
        // Blepharisma Macronuclear
        $triplets[15]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TGA )","(CAT |CAC )",
                        "(CAA |CAG |TAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Chlorophycean Mitochondrial
        $triplets[16]=array("(TTT |TTC )","(TTA |TTG |CT. |TAG )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TGA )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Trematode Mitochondrial
        $triplets[21]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC )","(ATG |ATA )","(GT. )","(TC. |AG. )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC |AAA )","(AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG |TGA )","(CG. )","(GG. )","(\S\S\S )");
        // Scenedesmus obliquus mitochondrial
        $triplets[22]=array("(TTT |TTC )","(TTA |TTG |CT. |TAG )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TCT |TCC |TCG |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TGA |TCA )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        // Thraustochytrium mitochondrial code
        $triplets[23]=array("(TTT |TTC )","(TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TTA |TAA |TAG |TGA )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");

        // place a space after each triplete in the sequence
        $temp = chunk_split($seq,3,' ');

        // replace triplets by corresponding amnoacid
        $peptide = preg_replace ($triplets[$genetic_code], $aminoacids, $temp);

        // return peptide sequence
        return $peptide;
    }
?>