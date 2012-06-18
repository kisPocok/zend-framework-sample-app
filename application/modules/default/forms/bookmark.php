<?php

class Default_Form_Bookmark extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $text = new Zend_Form_Element_Text('title');
        $text->setRequired(true)
            // Standard settings
            ->setLabel('Fancy title:')
            ->setAttrib('placeholder', 'nyan cat, iddqd, dina')
            ->setAttrib('maxLength', 30)
            // Filtering
            ->addFilters(array('StringTrim', new Zend_Filter_StringToLower()))
            // Validating
            ->addValidator(new Zend_Validate_StringLength(2, 30));
        $this->addElement($text);

        $url = new Zend_Form_Element_Text('url');
        $url->setRequired(true)
            // Standard settings
            ->setLabel('Page url:')
            ->setDescription('Start with http:// or https:// !')
            ->setAttrib('placeholder', 'http://blog.hu')
            ->setAttrib('maxLength', 255)
            // Filtering
            ->addFilter('StringTrim')
            // Validating
            ->addValidator('NotEmpty')
            ->addValidator(new Zend_Validate_StringLength(5, 255))
            ->addValidator('Regex', false, array('/^(http\:\/\/|https\:\/\/)/'));
        $this->addElement($url);

        $token = new Zend_Form_Element_Hash('token');
        $token->setTimeout(7200)
            ->setAttrib('id', 'token')
            ->setSalt('almafa');
        $this->addElement($token);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $this->addElement($submit);
    }
}