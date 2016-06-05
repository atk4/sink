<?php
class page_core_validation extends Page {

    function page_index(){
        $this->add('View_Hint')->set('This example clarifies the usage of the validation hooks in ATK 4.3+');

        $t = $this->add('Tabs');

        $t->addTabURL('./submit','onSubmit');
        $t->addTabURL('./validator','validator');
        $t->addTabURL('./validator2','validator2');
        $t->addTabURL('./validator3','validator3');
        $t->addTabURL('./callback','callback');
        $t->addTabURL('./class','class');
    }

    /**
     * This demonstrates backwards-compatible validation on submit.
     * http://book.agiletoolkit.org/views/form/validation.html#form-validation-examples
     *
     * Although documentation is using older method displayFieldError(),
     * which should be updated to simply error()
     */
    function page_submit() {
        $f = $this->add('Form');
        $f->addField('large_number');
        $f->addField('mandatory');
        $f->addField('mandatory2')->validateNotNull();
        $f->addSubmit();

        $f->onSubmit(function($f){
            if($f['large_number']<1000)return $f->error('large_number','is not large enough');
            if(!$f['mandatory'])return $f->error('mandatory','write something here');
            return 'all good';
        });
    }

    /**
     * This takes advantage of a new "validator" class, which is described here
     * http://book.agiletoolkit.org/controller/validator.html
     *
     * We are using field->validate() method, which basically will associate
     * validation string with the field
     */
    function page_validator() {
        $f = $this->add('Form');
        $f->addField('large_number')->validate('>1000?is not large enough');
        $f->addField('mandatory')->validate('required');
        $f->addField('mandatory2')->validateNotNull();
        $f->addSubmit();

        $f->onSubmit(function($f){
            return 'all good';
        });
    }

    /**
     * This also uses Controller_Validator, but calls it through $form->validate()
     * This method allows you to define multiple rules with a single call and is
     * directly passed to is(). Also when calling through validate(), this
     * also binds validation to 'validate' hook.
     */
    function page_validator2() {
        $f = $this->add('Form');
        $f->addField('large_number');
        $f->addField('mandatory');
        $f->addField('mandatory2')->validateNotNull();

        $f->validate([
            'large_number|>1000?is not large enough',
            'mandatory|required'
        ]);

        $f->addSubmit();

        $f->onSubmit(function($f){
            return 'all good';
        });
    }

    /**
     * This approach uses validator manually, and performs validation from onSubmit
     * method, but still uses the Controller_Validator. I recommend that you
     * use validator/validator2 approach.
     */
    function page_validator3() {
        $f = $this->add('Form');
        $f->addField('large_number');
        $f->addField('mandatory');
        $f->addField('mandatory2')->validateNotNull();


        $f->addSubmit();

        $f->onSubmit(function($f){
            $f->add('Controller_Validator')->is([
                'large_number|>1000?is not large enough',
                'mandatory|required'
            ])->now();

            return 'all good';
        });
    }

    /**
     * Controller_Validator also supports your own callbacks, like below. 
     * I am also using post-validate hook for my manual call-back, because
     * it's what validate() method uses.
     */
    function page_callback() {
        $f = $this->add('Form');
        $f->addField('large_number')->validate(function($v,$a){ if($a<1000)$v->fail('is not large enough'); });
        $f->addField('mandatory');
        $f->addHook('post-validate', function($f){
            if(!$f['mandatory'])
                $f->error('mandatory','write something here');

                /*
                 * also works, but longer
                throw $this->exception('write something here','ValidityCheck')
                ->setField('mandatory'); 
                 */
        });
        $f->addField('mandatory2')->validateField(function($f){ if(!$f->get())return 'must not be null'; });
        $f->addSubmit();

        $f->onSubmit(function($f){
            return 'all good';
        });
    }

    /**
     * This demonstrates how validation works in a custom class, with redefining 
     * performValidation method().
     */
    function page_class() {
        $f = $this->add('Form');
        $f->addField('LargeNumber','large_number');
        $f->addField('mandatory');
        $f->addField('mandatory2')->validateNotNull();
        $f->addSubmit();

        $f->onSubmit(function($f){
            return 'all good';
        });
    }
}

class Form_Field_LargeNumber extends Form_Field_Line {
    function performValidation() {
        if($this->get()<1000) $this->form->error($this->short_name,'too small');
    }
}
