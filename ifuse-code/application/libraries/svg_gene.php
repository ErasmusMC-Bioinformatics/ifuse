<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// http://www.tigercolor.com/color-lab/color-theory/color-theory-intro.htm
class svg_gene {
    private $CI            = null;
    private $arrowColor    = "#0010A5";
    
    /**
    * put your comment there...
    * 
    */
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
    * put your comment there...
    * 
    */
    public function __destruct() {}
    
    /**
    * put your comment there...
    * 
    * @param mixed $data
    * @param mixed $position
    * @return mixed
    */
    public function extractGene($data, $position) {
        if (!self::isGene($data)) {
            $gene = array('name'        => &$data["Gene.{$position}.name"       ],
                          'name2'       => &$data["Gene.{$position}.name2"      ],
                          'chrom'       => &$data["Gene.{$position}.chrom"      ],
                          'cds.start'   => intval($data["Gene.{$position}.cdsStart"   ]),
                          'cds.end'     => intval($data["Gene.{$position}.cdsEnd"     ]),
                          'tx.start'    => intval($data["Gene.{$position}.txStart"    ]),
                          'tx.end'      => intval($data["Gene.{$position}.txEnd"      ]),
                          'strand'      => &$data["Gene.{$position}.strand"     ],
                          'exon.starts' => &$data["Gene.{$position}.exonStarts" ],
                          'exon.ends'   => &$data["Gene.{$position}.exonEnds"   ],
                          'j.chrom'     => &$data["Junction.{$position}Chr"     ],
                          'j.start'     => intval($data["Junction.{$position}Start"   ]),
                          'j.end'       => intval($data["Junction.{$position}End"     ]),
                          'j.strand'    => &$data["Junction.{$position}Strand"  ],
                          'j.position'  => intval($data["Junction.{$position}Position"]),
                          'direction'   => &$data[strtolower($position).".direction"  ],
                          'position'    => $position,
                          'color'       => &$data[strtolower($position).".color"      ],
                          'e.min'       => &$data[strtolower($position).".emin"  ],
                          'e.max'       => &$data[strtolower($position).".emax"  ],
                          'e.length'    => &$data[strtolower($position).".elength"  ],
                          );
            return $gene;
        } else {
            return $data;
        }
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $gene
    * @return bool
    */
    private function isGene($gene) {
        return (is_array($gene               ) &&
                isset(   $gene['name'       ]) && 
                isset(   $gene['name2'      ]) && 
                isset(   $gene['chrom'      ]) && 
                isset(   $gene['cds.start'  ]) && 
                isset(   $gene['cds.end'    ]) && 
                isset(   $gene['tx.start'   ]) && 
                isset(   $gene['tx.end'     ]) && 
                isset(   $gene['strand'     ]) && 
                isset(   $gene['exon.starts']) && 
                isset(   $gene['exon.ends'  ]) && 
                isset(   $gene['j.chrom'    ]) && 
                isset(   $gene['j.start'    ]) && 
                isset(   $gene['j.end'      ]) && 
                isset(   $gene['j.strand'   ]) && 
                isset(   $gene['j.position' ]) &&
                isset(   $gene['direction'  ]) && 
                isset(   $gene['position'   ]) && 
                isset(   $gene['color'      ]) && 
                isset(   $gene['e.min'      ]) && 
                isset(   $gene['e.max'      ]) && 
                isset(   $gene['e.length'   ]));
    }
    
    /**
    * put your comment there...
    * 
    * @param int $a
    * @param int $b
    */
    private function getDifference($a, $b) {
        $a = intval($a);
        $b = intval($b);
        return $a > $b ? $a - $b : $b - $a;
    }
    
    /**
    * put your comment there...
    * 
    * @param int $int
    * @param array $range
    */
    private function inRange($int,$range){
        return ($int>=min($range) && $int<=max($range));
    }
    
    /**
    * put your comment there...
    * 
    * @param string $svg
    * @param int $width
    * @param int $height
    */
    public function getSVG($svg, $width = 800, $height = 70) {
        # <polygon points="0,5  10,5  10,0 20,10 10,20 10,15 0,15" style="stroke:#000000; fill:#cccccc;"/>
        # <rect x="20" y="0" height="20" width="20" style="stroke:#000000; fill: #cccccc"/>
        # <line x1="40"  y1="10" x2="60" y2="10" style="stroke:#000000;"/>
        
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"{$width}px\" height=\"{$height}px\">" . $svg . "</svg>";
    }
    
    /**
    * put your comment there...
    * 
    */
    public function getGeneInformationSVG(&$data, $width = 800) {
        $svg       = null;
        $left      = self::extractGene($data,"Left" );
        $right     = self::extractGene($data,"Right");
        
        $llength   = self::getDifference( $left['tx.start'], $left['tx.end']);
        $rlength   = self::getDifference($right['tx.start'],$right['tx.end']);
        $elength   = ($left['e.length']+ $right['e.length']);
        
        $maxlength = max(array($llength, $rlength, $elength));
        
        $svg .= self::createSVGGene($left,  $maxlength, $width, "translate(100,1)");
        $svg .= self::createSVGGene($right, $maxlength, $width, "translate(100,25)" );
        $svg .= self::createSVGFusionGene($left, $right, $maxlength, $width, "translate(100, 49)");
        #echo("$llength + $rlength = $elength ({$left['e.length']} | {$right['e.length']})");
        return $svg;
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $gene
    * @param int $totalwidth
    * @param int $totalbp
    * @param string $color
    */
    public function createSVGGeneBox($gene, $totalwidth, $totalbp) {
        $svg = "<line x1=\"0\"  y1=\"10\" x2=\"" . ($totalwidth / $totalbp * ((strtolower($gene['chrom']) != 'na') ? self::getDifference($gene['tx.start'], $gene['tx.end']) : self::getDifference($gene['j.start'], $gene['j.end']))) . "\" y2=\"10\" style=\"stroke:{$gene['color']};\"/>";
        
        if (self::isGene($gene)) {
            // Strand (Arrows)
            $direction = $gene['strand'] == '+';
            if (!empty($gene['strand']) && (strtolower($gene['strand']) != 'na')) {
                $width = ($totalwidth / $totalbp * (self::getDifference($gene['tx.start'], $gene['tx.end'])));
                $svg .= "<g transform=\"translate(" . ($direction ? -5 : $width) . ",0)\"><polygon points=\"" . ($direction ? "0,5 5,10 0,15" : "0,10 5,5 5,15") . "\" style=\"fill:#000000;\"/></g>";
            }
            
            // Exons (Boxes)
            $exon = 0;
            if ((strtolower($gene['chrom']) != 'na') && (is_array($gene['exon.starts'])) && (is_array($gene['exon.ends']))) {
                if (sizeof($gene['exon.starts']) == sizeof($gene['exon.ends'  ])) {
                    $length = sizeof($gene['exon.starts']);
                    for ($i = 0; $i < sizeof($gene['exon.starts']); $i++) {
                        $exon++;
                        if ($gene['exon.starts'][$i] != '' && $gene['exon.ends'  ][$i] != '' ) {
                            $fragment_length = ($totalwidth / $totalbp * ($gene['exon.ends'  ][$i] - $gene['exon.starts'][$i]));
                            $fragment_left   = ($totalwidth / $totalbp * ($gene['exon.starts'][$i] - $gene['tx.start']       ));
                            $svg .= "<rect x=\"$fragment_left\" y=\"0\" height=\"20\" width=\"$fragment_length\" style=\"fill: {$gene['color']}\" title=\"Exon: " . ($gene['strand'] == '-' ? $length - $exon : $exon) . "\"/>";
                        }
                    }
                }
            }
            
            
            // coding sequence (to mRNA)
            // cds.start | cds.end
            if (is_int($gene['tx.start']) && is_int($gene['tx.end'])) {
                $svg .= "<line x1=\"".($totalwidth / $totalbp * (self::getDifference(min($gene['cds.start'],$gene['cds.end']),min($gene['tx.start'],$gene['tx.end']))))."\" y1=\"7.5\" x2=\"".($totalwidth / $totalbp * ($this->getDifference($gene['cds.start'], $gene['cds.end'])))."\" y2=\"7.5\" style=\"stroke: #000\"/>";
            }
            
            
            
            
            
            
            
            
            
            
            
            // Transcription (to protein)
            
            // Junction site (Box)
            //$svg .= "<rect x=\"" . ($totalwidth / $totalbp * ($gene['j.start'] - $gene['tx.start'])) . "\" y=\"7.5\" height=\"5\" width=\"" . ($totalwidth / $totalbp * ($gene['j.end'] - $gene['j.start'])) . "\" style=\"fill: #ff0000\"/>";
            $svg .= "<rect x=\"" . ($totalwidth / $totalbp * ($gene['j.start'] - $gene['tx.start'])) . "\" y=\"7.5\" height=\"5\" width=\"5\" style=\"fill: #ff0000\"/>";
            
            // Junction (Arrow Down)
            $svg .= "<g transform=\"translate(" . ($totalwidth / $totalbp * ($gene['j.position'] - (($gene['tx.start'] != 0) ? $gene['tx.start'] : $gene['j.start']))) . ",10)\"><polygon points=\"-5,-7 -1,-5 -1,-10 1,-10 1,-5 5,-7 0,0\" style=\"fill:".$this->arrowColor.";\"/></g>";
        }
        return $svg;
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $gene
    * @param int $totalbp
    * @param int $totalwidth
    * @param string $transform
    */
    public function createSVGGene($gene, $totalbp = null, $totalwidth = 800, $transform = null) {
        if (self::isGene($gene)) {
            $svg = null;
            
            // Left Text
            $svg .= "<text x=\"-10\" y=\"10\" font-size=\"10px\" text-anchor=\"end\"  >{$gene['name2']} </text>";
            $svg .= "<text x=\"-10\" y=\"20\" font-size=\"10px\" text-anchor=\"end\"  >".((strtolower($gene['chrom']) != "na") ? ($gene['chrom'] . ":" .$gene['tx.start'] . "(" . $gene['strand'] . ")") : ($gene['j.chrom'] . ":" . $gene['j.start'] . "(" . $gene['j.strand'] .")"))."</text>";
            
            // Right Text
            $svg .= "<text x=\"" . ($totalwidth - (2 * 100) + 10) . "\" y=\"10\" font-size=\"10px\" text-anchor=\"start\">".((strtolower($gene['chrom']) != 'na') ? self::getDifference($gene['tx.start'], $gene['tx.end']) : self::getDifference($gene['j.start'], $gene['j.end'])) ."bp</text>";
            $svg .= "<text x=\"" . ($totalwidth - (2 * 100) + 10) . "\" y=\"20\" font-size=\"10px\" text-anchor=\"start\">".((strtolower($gene['chrom']) != "na") ? ($gene['chrom'] . ":" .$gene['tx.end']   . "(" . $gene['strand'] . ")") : ($gene['j.chrom'] . ":" . $gene['j.end']   . "(" . $gene['j.strand'] .")"))."</text>";
            
            // GeneBox
            $svg .= "<g id=\"{$gene['name2']}_{$gene['j.position']}\">" . self::createSVGGeneBox($gene, ($totalwidth - (2*100)), $totalbp) . "</g>";
            
            return "<g transform=\"{$transform}\">{$svg}</g>\n";
        } else {
            return null;
        }
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $gene
    * @param mixed $totalwidth
    * @param mixed $totalbp
    * @param mixed $transform
    */
    public function createSVGFusionGeneBox($gene, $totalwidth, $totalbp, $transform = null) {
        if (self::isGene($gene)) {
            $svg  = "<line x1=\"0\"  y1=\"10\" x2=\"" . ($totalwidth / $totalbp * $gene['e.length']) . "\" y2=\"10\" style=\"stroke:{$gene['color']}\"/>";
            
            // Strand (Arrows)
            $svg .= "<g transform=\"translate(" . ((strtolower($gene['position']) == 'left') ? -5 : ($totalwidth / $totalbp * $gene['e.length'])) .",0)\"><polygon points=\"" . (($gene['direction'] > 0) ? "0,5 5,10 0,15" : "0,10 5,5 5,15") . "\" style=\"fill:#000000;\"/></g>";
            
            // Exons (Boxes)
            $minus = ($gene['j.strand'] == '-');
            $range = array($gene['e.min'],$gene['e.max']);
            $exon  = 0;
            
            if ((strtolower($gene['chrom']) != 'na') && (is_array($gene['exon.starts'])) && (is_array($gene['exon.ends']))) {
                if (sizeof($gene['exon.starts']) == sizeof($gene['exon.ends'  ])) {
                    $length = sizeof($gene['exon.starts']);
                    for ($i = 0; $i < $length; $i++) {
                        $exon++;
                        if ($gene['exon.starts'][$i] != '' && $gene['exon.ends'  ][$i] != '') {
                            $start = intval($gene['exon.starts'][$i]);
                            $end   = intval($gene['exon.ends'  ][$i]);
                            if (self::inRange($start,$range) || self::inRange($end,$range)) {//TODO
                                //TODO: bug: start or end can be out of range
                                $fragment_length = ($totalwidth / $totalbp * ($gene['exon.ends'  ][$i] - $gene['exon.starts'][$i]));
                                $fragment_left   = ($totalwidth / $totalbp * ($minus ? (max($gene['e.min'],$gene['e.max']) - ($gene['exon.starts'][$i] + ($gene['exon.ends'  ][$i] - $gene['exon.starts'][$i]))) : ($gene['exon.starts'][$i] - min($gene['e.min'],$gene['e.max']))));
                                
                                //TODO: remove next line if correct
                                $svg .= "<rect x=\"$fragment_left\" y=\"0\" height=\"20\" width=\"$fragment_length\" style=\"fill: {$gene['color']}\" title=\"Exon: " . ($gene['strand'] == '-' ? $length - $exon : $exon) . "\"/>";
                            }
                        }
                    }
                }
            }
            
            // junction site
            $svg .= "<rect x=\"" . ((strtolower($gene['position']) == 'left') ? (($totalwidth / $totalbp * $gene['e.length'])-5) : 0) . "\" y=\"7.5\" height=\"5\" width=\"5\" style=\"fill: #ff0000\"/>";
            
            return "<g>{$svg}</g>\n";
        } else {
            return null;
        }
    }
    
    /**
    * put your comment there...
    * 
    * @param array $geneL
    * @param array $geneR
    * @param int $totalbp
    * @param int $totalwidth
    * @param string $transform
    */
    public function createSVGFusionGene($geneL, $geneR, $totalbp = null, $totalwidth = 800, $transform = null) {
        if (self::isGene($geneL) && self::isGene($geneR)) {
            $svg = null;
            
            // Left Text
            $svg .= "<text x=\"-10\" y=\"10\" font-size=\"10px\" text-anchor=\"end\">{$geneL['name2']}/{$geneR['name2']} </text>";
            $svg .= "<text x=\"-10\" y=\"20\" font-size=\"10px\" text-anchor=\"end\">...</text>";
            
            // Right Text
            $svg .= "<text x=\"" . ($totalwidth - (2 * 100) + 10) . "\" y=\"10\" font-size=\"10px\" text-anchor=\"start\">" . ($geneL['e.length'] + $geneR['e.length']) . "bp</text>";
            $svg .= "<text x=\"" . ($totalwidth - (2 * 100) + 10) . "\" y=\"20\" font-size=\"10px\" text-anchor=\"start\">...</text>";
            
            // Gene Left
            $svg .= "<g>" . self::createSVGFusionGeneBox($geneL, ($totalwidth - (2*100)), $totalbp) . "</g>";
            
            // Left offset
            $offsetx = (($totalwidth - (2*100)) / $totalbp * $geneL['e.length']);
            $offsety = (($geneL['direction'] == $geneR['direction'])?10:0);
            
            // Gene Right
            $svg .= "<g transform=\"translate(" . $offsetx . ",0)\">" . self::createSVGFusionGeneBox($geneR, ($totalwidth - (2*100)), $totalbp) . "</g>";
            
            // Junction or 'Connection' between genes
            $svg .= "<g transform=\"translate(" . $offsetx . "," . $offsety . ")\">" . (($geneL['direction'] == $geneR['direction']) ? "<polygon points=\"-5,-7 -1,-5 -1,-10 1,-10 1,-5 5,-7 0,0\" style=\"fill:".$this->arrowColor."\"/>" : "<line y2=\"5\" x2=\"5\" y1=\"-5\" x1=\"-5\" style=\"stroke:".$this->arrowColor.";\"/><line y2=\"-5\" x2=\"5\" y1=\"5\" x1=\"-5\" style=\"stroke:".$this->arrowColor.";\"/>") . "</g>";
            return "<g transform=\"{$transform}\">{$svg}</g>\n";
        } else {
            return null;
        }
    }
}

/* End of file svg_gene.php */
/* Location: ./application/libraries/svg_gene.php */
