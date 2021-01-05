<?php
    // Timezone -----------------------------------------------
    // --------------------------------------------------------
    ini_set('date.timezone', 'Europe/Amsterdam');
    
    // Default ------------------------------------------------
    // --------------------------------------------------------
    $lang['base_url']        = base_url();
    $lang['iFuse_title']     = "iFuse";
    $lang['iFuse_stitle']    = "structural variants from NGS data";
    $lang['iFuse_version']   = '1.0 alpha';
    $lang['iFuse_copyright'] = 'Copyright &copy; 2011 - '.date('Y').' Dpt. of Bioinformatics, Erasmus MC. All rights reserved.';
    
    // Main ---------------------------------------------------
    // --------------------------------------------------------
    $lang['main_iFile'              ] = 'NGS input file:';
    $lang['main_iFormat'            ] = 'Select input format';
    $lang['main_iHeader_label'      ] = 'Input has a first-line header';
    $lang['main_iHeaderT'           ] = 'True';
    $lang['main_iHeaderF'           ] = 'False';
    $lang['main_iRefGen_oColumn'    ] = 'Column';
    $lang['main_iRefGen_oAssembly'  ] = 'Assembly:';
    $lang['main_iRefGen_label'      ] = 'Reference genome';
    $lang['main_Submit'             ] = 'Submit';
    $lang['main_Reset'              ] = 'Reset';
    
    // Help ---------------------------------------------------
    // --------------------------------------------------------
    $lang['help_statment-privacy'   ] = 'Privacy statement';
    $lang['help_statment-privacy_t' ] = 'Privacy statement';
    $lang['help_statment-privacy_d' ] = 'We don&#39;t respect your privacy. Deal with it.';
    
    $lang['help_statment-cookie'    ] = 'Cookie statement';
    $lang['help_statment-cookie_t'  ] = 'Cookie statement';
    $lang['help_statment-cookie_d'  ] = 'This website uses cookies. We love cookies because it&#39;s the only thing internet has to feed it&#39;s websites!';
                                     
    $lang['help_statment-interest'  ] = 'Our Interests';
    $lang['help_statment-interest_t'] = 'Our Interests';
    $lang['help_statment-interest_d'] = 'Basically your data and analysis. Thanks for supporting our need for information.';
    
    $lang['help_statment-cite'      ] = 'How to cite us';
    $lang['help_statment-cite_t'    ] = 'How to cite us';
    $lang['help_statment-cite_d'    ] = 'Please don&#39;t.<br />It is embarresing enough that we are on the internet.';
    
    $lang['help_statment-more'      ] = $lang['iFuse_copyright'];
    $lang['help_statment-more_t'    ] = 'Information';
    $lang['help_statment-more_d'    ] = 
    "<table style=\\\"width:100%; border-collapse:collapse;\\\"><tr>" .
    "<td style=\\\"text-align:center;\\\"><a href=\\\"javascript:void(0);\\\" onclick=\\\"javascript: var title = (<r><![CDATA[\\\n{$lang['help_statment-privacy_t' ]}\\\n]]></r>).toString(); var message = (<r><![CDATA[\\\n{$lang['help_statment-privacy_d' ]}\\\n]]></r>).toString(); msgbox(title,message                  );\\\" message=\\\"message\\\">{$lang['help_statment-privacy' ]}</a></td>" .
    "<td style=\\\"text-align:center;\\\"><a href=\\\"javascript:void(0);\\\" onclick=\\\"javascript: var title = (<r><![CDATA[\\\n{$lang['help_statment-cookie_t'  ]}\\\n]]></r>).toString(); var message = (<r><![CDATA[\\\n{$lang['help_statment-cookie_d'  ]}\\\n]]></r>).toString(); msgbox(title,message                  );\\\" message=\\\"message\\\">{$lang['help_statment-cookie'  ]}</a></td>" .
    "</tr><tr>" .
    "<td style=\\\"text-align:center;\\\"><a href=\\\"javascript:void(0);\\\" onclick=\\\"javascript: var title = (<r><![CDATA[\\\n{$lang['help_statment-interest_t']}\\\n]]></r>).toString(); var message = (<r><![CDATA[\\\n{$lang['help_statment-interest_d']}\\\n]]></r>).toString(); msgbox(title,message,undefined,300,150);\\\" message=\\\"message\\\">{$lang['help_statment-interest']}</a></td>" .
    "<td style=\\\"text-align:center;\\\"><a href=\\\"javascript:void(0);\\\" onclick=\\\"javascript: var title = (<r><![CDATA[\\\n{$lang['help_statment-cite_t'    ]}\\\n]]></r>).toString(); var message = (<r><![CDATA[\\\n{$lang['help_statment-cite_d'    ]}\\\n]]></r>).toString(); msgbox(title,message,undefined,300,150);\\\" message=\\\"message\\\">{$lang['help_statment-cite'    ]}</a></td>" .
    "</tr></table>";
    
    $lang['help_ifile']                     = '?';
    $lang['help_ifile_t']                   = 'Help: Input Files';
    $lang['help_ifile_d']                   = 'Some explaination about the input files.<br /><hr style=\\\'border-width:0px; border-top:1px solid #000;\\\'/>When uploading a Complete Genomics file, please remove the first few lines so the first line in the file starts with &#8220;Id	LeftChr	LeftPosition	LeftStrand	LeftLength...&#8221;. Also you need to remove all the extra tabs at the end of each event-line so the amount of columns on those lines equels the header. This can be done with Notepad 2 or Notepad++ and an extended search/replace. Look for &#8220;&#92;&#92;t&#92;&#92;n&#8221; and replace this with &#8220;&#92;&#92;n&#8221;. Now everything should be working. ';
?>
