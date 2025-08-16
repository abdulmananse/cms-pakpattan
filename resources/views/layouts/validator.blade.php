<script src="{{ asset('js/jquery.validate.min.js') }}"></script>

<script type="text/javascript">

    $.validator.addMethod('alphaOnly', function (value) { 
        const regex = /^[a-zA-Z ]*$/;
        return regex.test(value); 
    }, 'Please enter only alphabets');

    $.validator.addMethod('gte', function (value, element, param) {
        if(parseInt($(param).val(), 10) >= 0) {
          return this.optional(element) || parseInt(value, 10) >= parseInt($(param).val(), 10);
        }
        return true;
    }, function(param, element) {
        return 'Value must be greater than or equal to ' + parseInt($(param).val(), 10);
    });

    $.validator.addMethod('mobileNo', function (value) { 
        return validateMobileNumber(value);
    }, 'Please enter valid number');

    $.validator.addMethod('cnic', function (value) { 
        return validateCnicNumber(value);
    }, 'Please enter valid cnic number');

    $.validator.addMethod("noSpace", function(value, element) { 
        return value.indexOf(" ") < 0 && value != ""; 
    }, "No space please and don't leave it empty");
    
    const requiredAlphabetsRules = {
        required: true,
        alphaOnly: true
    };
    
    const requiredmobileNoRules = {
        required: true,
        mobileNo: true
    };

    /**
     * Validate Mobile Number
     * @param $number
     */
    function validateMobileNumber(number)
    {
      if(number == '') {
        return true;
      }

      const regex = /^03[0-9]{2}-[0-9]{7}$/;
      return regex.test(number);
    }

    /**
     * Validate Cnic Number
     * @param $number
     */
    function validateCnicNumber(number)
    {
        if(number == '') {
          return true;
        }

        const regex = /^[1-9]{1}[0-9]{4}-[0-9]{7}-[0-9]{1}$/;
        $format = regex.test(number);
        $repeat0 = /^[0-9]*[0]{6}[0-9]*$/.test(number);
        $repeat1 = /^[0-9]*[1]{6}[0-9]*$/.test(number);
        $repeat2 = /^[0-9]*[2]{6}[0-9]*$/.test(number);
        $repeat3 = /^[0-9]*[3]{6}[0-9]*$/.test(number);
        $repeat4 = /^[0-9]*[4]{6}[0-9]*$/.test(number);
        $repeat5 = /^[0-9]*[5]{6}[0-9]*$/.test(number);
        $repeat6 = /^[0-9]*[6]{6}[0-9]*$/.test(number);
        $repeat7 = /^[0-9]*[7]{6}[0-9]*$/.test(number);
        $repeat8 = /^[0-9]*[8]{6}[0-9]*$/.test(number);
        $repeat9 = /^[0-9]*[9]{6}[0-9]*$/.test(number);

        if ($format && !$repeat0 && !$repeat1 && !$repeat2 && !$repeat3 && !$repeat4 && !$repeat5 && !$repeat6 && !$repeat7 && !$repeat8 && !$repeat9) {
            return true;
        }
        return false;
    }
</script>