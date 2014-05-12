<?php

/* WebProfilerBundle:Collector:exception.css.twig */
class __TwigTemplate_860f0ef2a15f250132b4920a3174e6d978cdc3bde3facc420cea7c42bc48212f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo ".sf-reset .traces {
    padding-bottom: 14px;
}
.sf-reset .traces li {
    font-size: 12px;
    color: #868686;
    padding: 5px 4px;
    list-style-type: decimal;
    margin-left: 20px;
    white-space: break-word;
}
.sf-reset #logs .traces li.error {
    font-style: normal;
    color: #AA3333;
    background: #f9ecec;
}
.sf-reset #logs .traces li.warning {
    font-style: normal;
    background: #ffcc00;
}
/* fix for Opera not liking empty <li> */
.sf-reset .traces li:after {
    content: \"\\00A0\";
}
.sf-reset .trace {
    border: 1px solid #D3D3D3;
    padding: 10px;
    overflow: auto;
    margin: 10px 0 20px;
}
.sf-reset .block-exception {
    border-radius: 16px;
    margin-bottom: 20px;
    background-color: #f6f6f6;
    border: 1px solid #dfdfdf;
    padding: 30px 28px;
    word-wrap: break-word;
    overflow: hidden;
}
.sf-reset .block-exception div {
    color: #313131;
    font-size: 10px;
}
.sf-reset .block-exception-detected .illustration-exception,
.sf-reset .block-exception-detected .text-exception {
    float: left;
}
.sf-reset .block-exception-detected .illustration-exception {
    width: 152px;
}
.sf-reset .block-exception-detected .text-exception {
    width: 670px;
    padding: 30px 44px 24px 46px;
    position: relative;
}
.sf-reset .text-exception .open-quote,
.sf-reset .text-exception .close-quote {
    position: absolute;
}
.sf-reset .open-quote {
    top: 0;
    left: 0;
}
.sf-reset .close-quote {
    bottom: 0;
    right: 50px;
}
.sf-reset .block-exception p {
    font-family: Arial, Helvetica, sans-serif;
}
.sf-reset .block-exception p a,
.sf-reset .block-exception p a:hover {
    color: #565656;
}
.sf-reset .logs h2 {
    float: left;
    width: 654px;
}
.sf-reset .error-count {
    float: right;
    width: 170px;
    text-align: right;
}
.sf-reset .error-count span {
    display: inline-block;
    background-color: #aacd4e;
    border-radius: 6px;
    padding: 4px;
    color: white;
    margin-right: 2px;
    font-size: 11px;
    font-weight: bold;
}
.sf-reset .toggle {
    vertical-align: middle;
}
.sf-reset .linked ul,
.sf-reset .linked li {
    display: inline;
}
.sf-reset #output-content {
    color: #000;
    font-size: 12px;
}
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:exception.css.twig";
    }

    public function getDebugInfo()
    {
        return array (  175 => 65,  161 => 63,  462 => 202,  446 => 197,  441 => 196,  439 => 195,  429 => 188,  422 => 184,  415 => 180,  408 => 176,  394 => 168,  380 => 160,  373 => 156,  361 => 146,  351 => 141,  342 => 137,  335 => 134,  329 => 131,  325 => 129,  323 => 128,  303 => 122,  300 => 121,  289 => 113,  286 => 112,  270 => 102,  267 => 101,  262 => 98,  256 => 96,  248 => 94,  233 => 87,  226 => 84,  216 => 79,  213 => 78,  207 => 75,  200 => 72,  197 => 71,  194 => 70,  191 => 67,  185 => 66,  181 => 65,  178 => 66,  172 => 64,  165 => 60,  153 => 56,  150 => 55,  134 => 54,  76 => 31,  84 => 24,  70 => 19,  110 => 41,  97 => 33,  90 => 27,  129 => 64,  104 => 46,  77 => 34,  65 => 23,  100 => 39,  81 => 23,  20 => 1,  152 => 85,  146 => 68,  127 => 73,  118 => 49,  114 => 75,  53 => 12,  34 => 5,  126 => 56,  113 => 48,  479 => 308,  475 => 307,  471 => 306,  467 => 305,  463 => 304,  459 => 303,  455 => 302,  449 => 198,  431 => 189,  425 => 284,  419 => 281,  401 => 172,  395 => 269,  389 => 266,  383 => 263,  377 => 260,  353 => 239,  348 => 140,  345 => 235,  338 => 135,  331 => 21,  326 => 19,  320 => 127,  315 => 125,  311 => 13,  306 => 11,  302 => 10,  299 => 9,  296 => 8,  290 => 7,  284 => 420,  282 => 235,  277 => 232,  275 => 105,  188 => 146,  170 => 95,  160 => 130,  58 => 24,  23 => 1,  480 => 162,  474 => 161,  469 => 158,  461 => 155,  457 => 153,  453 => 199,  444 => 149,  440 => 148,  437 => 290,  435 => 146,  430 => 144,  427 => 143,  423 => 142,  413 => 278,  409 => 132,  407 => 275,  402 => 130,  398 => 129,  393 => 126,  387 => 164,  384 => 121,  381 => 120,  379 => 119,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 143,  341 => 231,  337 => 103,  322 => 101,  314 => 99,  312 => 124,  309 => 97,  305 => 95,  298 => 120,  294 => 90,  285 => 89,  283 => 88,  278 => 106,  268 => 85,  264 => 84,  258 => 81,  252 => 80,  247 => 78,  241 => 90,  229 => 85,  220 => 81,  214 => 69,  177 => 65,  169 => 60,  140 => 58,  132 => 51,  128 => 80,  107 => 36,  61 => 24,  273 => 96,  269 => 94,  254 => 92,  243 => 88,  240 => 86,  238 => 85,  235 => 74,  230 => 82,  227 => 81,  224 => 71,  221 => 77,  219 => 76,  217 => 75,  208 => 68,  204 => 72,  179 => 69,  159 => 61,  143 => 56,  135 => 76,  119 => 40,  102 => 40,  71 => 29,  67 => 24,  63 => 27,  59 => 14,  38 => 6,  94 => 39,  89 => 20,  85 => 33,  75 => 17,  68 => 26,  56 => 16,  87 => 34,  21 => 2,  26 => 3,  93 => 32,  88 => 30,  78 => 26,  46 => 13,  27 => 3,  44 => 10,  31 => 4,  28 => 3,  201 => 92,  196 => 90,  183 => 82,  171 => 61,  166 => 71,  163 => 90,  158 => 62,  156 => 58,  151 => 59,  142 => 86,  138 => 84,  136 => 56,  121 => 50,  117 => 69,  105 => 34,  91 => 41,  62 => 23,  49 => 14,  24 => 4,  25 => 5,  19 => 1,  79 => 35,  72 => 27,  69 => 24,  47 => 22,  40 => 12,  37 => 24,  22 => 2,  246 => 93,  157 => 56,  145 => 46,  139 => 45,  131 => 75,  123 => 42,  120 => 51,  115 => 48,  111 => 47,  108 => 58,  101 => 46,  98 => 40,  96 => 37,  83 => 33,  74 => 30,  66 => 24,  55 => 16,  52 => 18,  50 => 10,  43 => 12,  41 => 7,  35 => 6,  32 => 5,  29 => 3,  209 => 82,  203 => 73,  199 => 67,  193 => 73,  189 => 71,  187 => 84,  182 => 143,  176 => 63,  173 => 65,  168 => 61,  164 => 59,  162 => 59,  154 => 60,  149 => 51,  147 => 54,  144 => 49,  141 => 51,  133 => 61,  130 => 46,  125 => 51,  122 => 77,  116 => 39,  112 => 59,  109 => 34,  106 => 50,  103 => 36,  99 => 31,  95 => 38,  92 => 31,  86 => 35,  82 => 29,  80 => 32,  73 => 20,  64 => 23,  60 => 22,  57 => 23,  54 => 20,  51 => 22,  48 => 19,  45 => 10,  42 => 10,  39 => 8,  36 => 7,  33 => 4,  30 => 3,);
    }
}
