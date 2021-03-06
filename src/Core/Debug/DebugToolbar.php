<?php

namespace Core\Debug;

class DebugToolbar
{
    protected $widgets = array();
    protected $windows = array();
    
    public function addWidget($widget, $width = false, $onClickShow = ''){
        if($widget){
            $width = $width ? 'min-width:'.$width.'px;':null;
            $onClickShow = $onClickShow ? 'data-show="'.$onClickShow.'"':null;
            $this->widgets[] = '<div '.$onClickShow.' class="cyril-widget" style="'.$width.'">'.$widget.'</div>';
        }
    }
    
    public function addWindow($window, $id){
        $this->windows[] = '<div class="cyril-window" id="'.$id.'"><div class="cyril-close">X</div>'.$window.'</div>';
    }
    
    public function getCloseButton(){
        return '<div class="cyril-close">X</div>';
    }
    
    public function renderToolbar()
    {
        $container = '<div class="cyril-debug">' .
                preg_replace('/\n/','',implode('', $this->widgets))
                . $this->getCloseButton() . '</div>';

        $windows = preg_replace('/\n/','',implode('', $this->windows));

        return $this->getStyles(). $windows . $container . $this->getJs();
    }
    
    public function getJs(){
        $styles = 
        "
            <script>
            //hiding
            var el = document.querySelectorAll('.cyril-close');
            var l = el.length;
            for (index = 0; index < l; ++index) {
                el[index].addEventListener('click', function(){
                    var p = this.parentNode;
                    p.style.display = 'none';
                }, false);
            }
            
            //showing
            var el = document.querySelectorAll('.cyril-widget[data-show]');
            var l = el.length;
            for (index = 0; index < l; ++index) {
                el[index].addEventListener('click', function(){
                    if(this.hasAttribute('data-show')){
                        var el = document.getElementById(this.getAttribute('data-show'));
                        if(el.style.display != 'none')
                            el.style.display = 'none';
                        else
                            el.style.display = 'block';
                        window.scrollTo(0);
                    }
                }, false);
            }
            </script>
        ";
        
        return $styles;
    }
    
    public function getStyles(){
        $styles =  
        '<style>
            .cyril-debug{
                position:fixed; 
                bottom: 0px;
                font-family: Verdana, Arial; 
                font-size: 11px;
                background: #e4e4e4; 
                width: 100%; 
                margin:0; 
                left:0;
                padding: 5px 0;
                box-shadow: 0 0px 18px rgba(0,0,0,.5);
            }

            .cyril-debug .cyril-widget{
                float:left; 
                margin-left: 5px; 
                padding: 5px; 
                border-left:1px solid rgba(0,0,0,.4); 
                box-shadow: -1px 0px 0 rgba(255,255,255,.9);
            }
            
            .cyril-debug .cyril-widget[data-show]{
                cursor: pointer;
            }

            .cyril-debug .cyril-widget:first-child{
                border-left: 0;
                box-shadow:none;
            }

            .cyril-debug .cyril-widget span.cyril-session{
                padding: 2px 4px;
                border-radius: 5px;
                border-bottom-left-radius: 0;
                box-shadow: 1px 1px 0 rgba(255,255,255,.9);
            }

            .cyril-debug .cyril-widget span.cyril-session.cyril-red{
                background: #E6203A;
            }
            .cyril-debug .cyril-widget span.cyril-session.cyril-green{
                background: #ACD119;
            }

            .cyril-debug .cyril-close{
                float:right;
                margin:5px 10px 5px 5px;
                cursor: pointer;
            }
            
            .cyril-close{
                cursor: pointer;
            }
            
            .cyril-sql-log{
                list-style: none;
                box-sizing: border-box;
            }
            
            .cyril-sql-log li{
                padding: 5px;
            }
            .cyril-sql-log li:nth-child(even){
                background: #D6D6D6;
            }
            
            .cyril-sql-log p.cyril-query{
                font-size: 14px; 
            }
            
            .cyril-sql-log p.cyril-params, .cyril-sql-log p.cyril-time{
                margin:0;
            }

            .cyril-window{
                display:none;
                position:absolute;
                top: 0;
                left: 0;
                background: #F2F2F2;
                width: 100%;
                font-family: Verdana, Arial; 
                font-size: 11px;
                box-sizing: border-box;
            }
            
            .cyril-window{
                float: left;
            }
            
            .cyril-session-list{
                list-style: none;
                font-size: 14px;
            }
            
            .cyril-session-list li{
                margin: 10px 0;
            }
            
            .cyril-session-list li span.cyril-session-name{
                font-weight: 800;
            }
            </style>';
        
        return preg_replace('/\n/', '', $styles);
    }
}

?>
