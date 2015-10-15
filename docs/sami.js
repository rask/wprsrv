
(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:Wprsrv" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Wprsrv.html">Wprsrv</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Wprsrv_Admin" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Wprsrv/Admin.html">Admin</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Wprsrv_Admin_AdminMenu" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Wprsrv/Admin/AdminMenu.html">AdminMenu</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Admin_ReservableCalendar" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Wprsrv/Admin/ReservableCalendar.html">ReservableCalendar</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Wprsrv_Forms" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Wprsrv/Forms.html">Forms</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Wprsrv_Forms_Fields" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Wprsrv/Forms/Fields.html">Fields</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Wprsrv_Forms_Fields_CalendarEndField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/CalendarEndField.html">CalendarEndField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_CalendarField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/CalendarField.html">CalendarField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_CalendarRangeField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/CalendarRangeField.html">CalendarRangeField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_CalendarStartField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/CalendarStartField.html">CalendarStartField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_CheckboxField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/CheckboxField.html">CheckboxField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_CheckboxGroup" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/CheckboxGroup.html">CheckboxGroup</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_FormField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/FormField.html">FormField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_RadioField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/RadioField.html">RadioField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_RadioGroup" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/RadioGroup.html">RadioGroup</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_SelectField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/SelectField.html">SelectField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_TextField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/TextField.html">TextField</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Forms_Fields_TextareaField" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/Forms/Fields/TextareaField.html">TextareaField</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Wprsrv_Forms_ReservationForm" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Wprsrv/Forms/ReservationForm.html">ReservationForm</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Wprsrv_PostTypes" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Wprsrv/PostTypes.html">PostTypes</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Wprsrv_PostTypes_Objects" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Wprsrv/PostTypes/Objects.html">Objects</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Wprsrv_PostTypes_Objects_Reservable" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/PostTypes/Objects/Reservable.html">Reservable</a>                    </div>                </li>                            <li data-name="class:Wprsrv_PostTypes_Objects_Reservation" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Wprsrv/PostTypes/Objects/Reservation.html">Reservation</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Wprsrv_PostTypes_PostType" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Wprsrv/PostTypes/PostType.html">PostType</a>                    </div>                </li>                            <li data-name="class:Wprsrv_PostTypes_Reservable" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Wprsrv/PostTypes/Reservable.html">Reservable</a>                    </div>                </li>                            <li data-name="class:Wprsrv_PostTypes_Reservation" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Wprsrv/PostTypes/Reservation.html">Reservation</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Wprsrv_Traits" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Wprsrv/Traits.html">Traits</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Wprsrv_Traits_CastsToPost" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Wprsrv/Traits/CastsToPost.html">CastsToPost</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Wprsrv_Email" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Wprsrv/Email.html">Email</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Logger" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Wprsrv/Logger.html">Logger</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Plugin" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Wprsrv/Plugin.html">Plugin</a>                    </div>                </li>                            <li data-name="class:Wprsrv_Settings" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Wprsrv/Settings.html">Settings</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Wprsrv.html", "name": "Wprsrv", "doc": "Namespace Wprsrv"},{"type": "Namespace", "link": "Wprsrv/Admin.html", "name": "Wprsrv\\Admin", "doc": "Namespace Wprsrv\\Admin"},{"type": "Namespace", "link": "Wprsrv/Forms.html", "name": "Wprsrv\\Forms", "doc": "Namespace Wprsrv\\Forms"},{"type": "Namespace", "link": "Wprsrv/Forms/Fields.html", "name": "Wprsrv\\Forms\\Fields", "doc": "Namespace Wprsrv\\Forms\\Fields"},{"type": "Namespace", "link": "Wprsrv/PostTypes.html", "name": "Wprsrv\\PostTypes", "doc": "Namespace Wprsrv\\PostTypes"},{"type": "Namespace", "link": "Wprsrv/PostTypes/Objects.html", "name": "Wprsrv\\PostTypes\\Objects", "doc": "Namespace Wprsrv\\PostTypes\\Objects"},{"type": "Namespace", "link": "Wprsrv/Traits.html", "name": "Wprsrv\\Traits", "doc": "Namespace Wprsrv\\Traits"},
            
            {"type": "Class", "fromName": "Wprsrv\\Admin", "fromLink": "Wprsrv/Admin.html", "link": "Wprsrv/Admin/AdminMenu.html", "name": "Wprsrv\\Admin\\AdminMenu", "doc": "&quot;Class AdminMenu&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Admin\\AdminMenu", "fromLink": "Wprsrv/Admin/AdminMenu.html", "link": "Wprsrv/Admin/AdminMenu.html#method___construct", "name": "Wprsrv\\Admin\\AdminMenu::__construct", "doc": "&quot;Hook to admin menu.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Admin\\AdminMenu", "fromLink": "Wprsrv/Admin/AdminMenu.html", "link": "Wprsrv/Admin/AdminMenu.html#method_adminMenu", "name": "Wprsrv\\Admin\\AdminMenu::adminMenu", "doc": "&quot;Manage the admin menu. Create top level items and sub items.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Admin\\AdminMenu", "fromLink": "Wprsrv/Admin/AdminMenu.html", "link": "Wprsrv/Admin/AdminMenu.html#method_generateAdminPage", "name": "Wprsrv\\Admin\\AdminMenu::generateAdminPage", "doc": "&quot;Empty page that should redirect straight to submenu item.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Admin", "fromLink": "Wprsrv/Admin.html", "link": "Wprsrv/Admin/ReservableCalendar.html", "name": "Wprsrv\\Admin\\ReservableCalendar", "doc": "&quot;Class ReservableCalendar&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Admin\\ReservableCalendar", "fromLink": "Wprsrv/Admin/ReservableCalendar.html", "link": "Wprsrv/Admin/ReservableCalendar.html#method___construct", "name": "Wprsrv\\Admin\\ReservableCalendar::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Admin\\ReservableCalendar", "fromLink": "Wprsrv/Admin/ReservableCalendar.html", "link": "Wprsrv/Admin/ReservableCalendar.html#method_render", "name": "Wprsrv\\Admin\\ReservableCalendar::render", "doc": "&quot;Render the calendar.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv", "fromLink": "Wprsrv.html", "link": "Wprsrv/Email.html", "name": "Wprsrv\\Email", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method___construct", "name": "Wprsrv\\Email::__construct", "doc": "&quot;Constructor. Set the email type.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_setTemplate", "name": "Wprsrv\\Email::setTemplate", "doc": "&quot;Set the wanted email template to use when sending.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_sendWith", "name": "Wprsrv\\Email::sendWith", "doc": "&quot;Send with an array of data. The template file will be filled with the data.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_send", "name": "Wprsrv\\Email::send", "doc": "&quot;Simple send. If the template contains no customized data this is a good option.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_getSubject", "name": "Wprsrv\\Email::getSubject", "doc": "&quot;Get the subject of the email.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_setSubject", "name": "Wprsrv\\Email::setSubject", "doc": "&quot;Set the subject for this email.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_getBody", "name": "Wprsrv\\Email::getBody", "doc": "&quot;Return the body of the email.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_setBody", "name": "Wprsrv\\Email::setBody", "doc": "&quot;Set the email body.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_getTo", "name": "Wprsrv\\Email::getTo", "doc": "&quot;Get the receiving address.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_setTo", "name": "Wprsrv\\Email::setTo", "doc": "&quot;Set the receiving address.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_getHeaders", "name": "Wprsrv\\Email::getHeaders", "doc": "&quot;Get the email headers.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_setHeaders", "name": "Wprsrv\\Email::setHeaders", "doc": "&quot;Set the email headers.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_setFailureCallback", "name": "Wprsrv\\Email::setFailureCallback", "doc": "&quot;Set the email sending failure callback.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Email", "fromLink": "Wprsrv/Email.html", "link": "Wprsrv/Email.html#method_setSuccessCallback", "name": "Wprsrv\\Email::setSuccessCallback", "doc": "&quot;Sent the email sending success callback.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/CalendarEndField.html", "name": "Wprsrv\\Forms\\Fields\\CalendarEndField", "doc": "&quot;Class CalendarField&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\CalendarEndField", "fromLink": "Wprsrv/Forms/Fields/CalendarEndField.html", "link": "Wprsrv/Forms/Fields/CalendarEndField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\CalendarEndField::generateMarkup", "doc": "&quot;Generate HTML for field.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/CalendarField.html", "name": "Wprsrv\\Forms\\Fields\\CalendarField", "doc": "&quot;Class CalendarField&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\CalendarField", "fromLink": "Wprsrv/Forms/Fields/CalendarField.html", "link": "Wprsrv/Forms/Fields/CalendarField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\CalendarField::generateMarkup", "doc": "&quot;Generate HTML for field.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/CalendarRangeField.html", "name": "Wprsrv\\Forms\\Fields\\CalendarRangeField", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\CalendarRangeField", "fromLink": "Wprsrv/Forms/Fields/CalendarRangeField.html", "link": "Wprsrv/Forms/Fields/CalendarRangeField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\CalendarRangeField::generateMarkup", "doc": "&quot;Generate field HTML markup. Return as a string.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/CalendarStartField.html", "name": "Wprsrv\\Forms\\Fields\\CalendarStartField", "doc": "&quot;Class CalendarField&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\CalendarStartField", "fromLink": "Wprsrv/Forms/Fields/CalendarStartField.html", "link": "Wprsrv/Forms/Fields/CalendarStartField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\CalendarStartField::generateMarkup", "doc": "&quot;Generate HTML for field.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/CheckboxField.html", "name": "Wprsrv\\Forms\\Fields\\CheckboxField", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\CheckboxField", "fromLink": "Wprsrv/Forms/Fields/CheckboxField.html", "link": "Wprsrv/Forms/Fields/CheckboxField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\CheckboxField::generateMarkup", "doc": "&quot;Generate field HTML markup. Return as a string.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/CheckboxGroup.html", "name": "Wprsrv\\Forms\\Fields\\CheckboxGroup", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\CheckboxGroup", "fromLink": "Wprsrv/Forms/Fields/CheckboxGroup.html", "link": "Wprsrv/Forms/Fields/CheckboxGroup.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\CheckboxGroup::generateMarkup", "doc": "&quot;Generate field HTML markup. Return as a string.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/FormField.html", "name": "Wprsrv\\Forms\\Fields\\FormField", "doc": "&quot;Class FormField&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\FormField", "fromLink": "Wprsrv/Forms/Fields/FormField.html", "link": "Wprsrv/Forms/Fields/FormField.html#method___construct", "name": "Wprsrv\\Forms\\Fields\\FormField::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\FormField", "fromLink": "Wprsrv/Forms/Fields/FormField.html", "link": "Wprsrv/Forms/Fields/FormField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\FormField::generateMarkup", "doc": "&quot;Generate field HTML markup. Return as a string.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/RadioField.html", "name": "Wprsrv\\Forms\\Fields\\RadioField", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\RadioField", "fromLink": "Wprsrv/Forms/Fields/RadioField.html", "link": "Wprsrv/Forms/Fields/RadioField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\RadioField::generateMarkup", "doc": "&quot;Generate field HTML markup. Return as a string.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/RadioGroup.html", "name": "Wprsrv\\Forms\\Fields\\RadioGroup", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\RadioGroup", "fromLink": "Wprsrv/Forms/Fields/RadioGroup.html", "link": "Wprsrv/Forms/Fields/RadioGroup.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\RadioGroup::generateMarkup", "doc": "&quot;Generate field HTML markup. Return as a string.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/SelectField.html", "name": "Wprsrv\\Forms\\Fields\\SelectField", "doc": "&quot;Class TextField&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\SelectField", "fromLink": "Wprsrv/Forms/Fields/SelectField.html", "link": "Wprsrv/Forms/Fields/SelectField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\SelectField::generateMarkup", "doc": "&quot;Generate the field markup for use elsewhere.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/TextField.html", "name": "Wprsrv\\Forms\\Fields\\TextField", "doc": "&quot;Class TextField&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\TextField", "fromLink": "Wprsrv/Forms/Fields/TextField.html", "link": "Wprsrv/Forms/Fields/TextField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\TextField::generateMarkup", "doc": "&quot;Generate the field markup for use elsewhere.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms\\Fields", "fromLink": "Wprsrv/Forms/Fields.html", "link": "Wprsrv/Forms/Fields/TextareaField.html", "name": "Wprsrv\\Forms\\Fields\\TextareaField", "doc": "&quot;Class TextareaField&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\Fields\\TextareaField", "fromLink": "Wprsrv/Forms/Fields/TextareaField.html", "link": "Wprsrv/Forms/Fields/TextareaField.html#method_generateMarkup", "name": "Wprsrv\\Forms\\Fields\\TextareaField::generateMarkup", "doc": "&quot;Generate the field markup for use elsewhere.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\Forms", "fromLink": "Wprsrv/Forms.html", "link": "Wprsrv/Forms/ReservationForm.html", "name": "Wprsrv\\Forms\\ReservationForm", "doc": "&quot;Class ReservationForm&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Forms\\ReservationForm", "fromLink": "Wprsrv/Forms/ReservationForm.html", "link": "Wprsrv/Forms/ReservationForm.html#method___construct", "name": "Wprsrv\\Forms\\ReservationForm::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Forms\\ReservationForm", "fromLink": "Wprsrv/Forms/ReservationForm.html", "link": "Wprsrv/Forms/ReservationForm.html#method_handleFormSubmit", "name": "Wprsrv\\Forms\\ReservationForm::handleFormSubmit", "doc": "&quot;Handle a submitted reservation form.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Forms\\ReservationForm", "fromLink": "Wprsrv/Forms/ReservationForm.html", "link": "Wprsrv/Forms/ReservationForm.html#method_enqueueScripts", "name": "Wprsrv\\Forms\\ReservationForm::enqueueScripts", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Forms\\ReservationForm", "fromLink": "Wprsrv/Forms/ReservationForm.html", "link": "Wprsrv/Forms/ReservationForm.html#method_hiddenFormFields", "name": "Wprsrv\\Forms\\ReservationForm::hiddenFormFields", "doc": "&quot;Hidden form fields.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Forms\\ReservationForm", "fromLink": "Wprsrv/Forms/ReservationForm.html", "link": "Wprsrv/Forms/ReservationForm.html#method_render", "name": "Wprsrv\\Forms\\ReservationForm::render", "doc": "&quot;Render the form. The included template has $this available.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv", "fromLink": "Wprsrv.html", "link": "Wprsrv/Logger.html", "name": "Wprsrv\\Logger", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method___construct", "name": "Wprsrv\\Logger::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_emergency", "name": "Wprsrv\\Logger::emergency", "doc": "&quot;System is unusable.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_alert", "name": "Wprsrv\\Logger::alert", "doc": "&quot;Action must be taken immediately.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_critical", "name": "Wprsrv\\Logger::critical", "doc": "&quot;Critical conditions.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_error", "name": "Wprsrv\\Logger::error", "doc": "&quot;Runtime errors that do not require immediate action but should typically\nbe logged and monitored.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_warning", "name": "Wprsrv\\Logger::warning", "doc": "&quot;Exceptional occurrences that are not errors.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_notice", "name": "Wprsrv\\Logger::notice", "doc": "&quot;Normal but significant events.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_info", "name": "Wprsrv\\Logger::info", "doc": "&quot;Interesting events.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_debug", "name": "Wprsrv\\Logger::debug", "doc": "&quot;Detailed debug information.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Logger", "fromLink": "Wprsrv/Logger.html", "link": "Wprsrv/Logger.html#method_log", "name": "Wprsrv\\Logger::log", "doc": "&quot;Logs with an arbitrary level.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv", "fromLink": "Wprsrv.html", "link": "Wprsrv/Plugin.html", "name": "Wprsrv\\Plugin", "doc": "&quot;Class Plugin&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Plugin", "fromLink": "Wprsrv/Plugin.html", "link": "Wprsrv/Plugin.html#method___construct", "name": "Wprsrv\\Plugin::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Plugin", "fromLink": "Wprsrv/Plugin.html", "link": "Wprsrv/Plugin.html#method_initialize", "name": "Wprsrv\\Plugin::initialize", "doc": "&quot;Initializations.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Plugin", "fromLink": "Wprsrv/Plugin.html", "link": "Wprsrv/Plugin.html#method_activate", "name": "Wprsrv\\Plugin::activate", "doc": "&quot;Fired on WP plugin activation hook. No output allowed.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Plugin", "fromLink": "Wprsrv/Plugin.html", "link": "Wprsrv/Plugin.html#method_deactivate", "name": "Wprsrv\\Plugin::deactivate", "doc": "&quot;Fired on WP plugin deactivation hook. No output allowed.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Plugin", "fromLink": "Wprsrv/Plugin.html", "link": "Wprsrv/Plugin.html#method_make", "name": "Wprsrv\\Plugin::make", "doc": "&quot;Instance container method&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Plugin", "fromLink": "Wprsrv/Plugin.html", "link": "Wprsrv/Plugin.html#method_isInitialized", "name": "Wprsrv\\Plugin::isInitialized", "doc": "&quot;Has this plugin instance been initialized?&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\PostTypes\\Objects", "fromLink": "Wprsrv/PostTypes/Objects.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html", "name": "Wprsrv\\PostTypes\\Objects\\Reservable", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_getReservations", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::getReservations", "doc": "&quot;Get all reservations that have been mapped for this reservable.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_getDisabledDaysData", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::getDisabledDaysData", "doc": "&quot;Get the data for all disabled days for a reservable item. This includes blocked time which has been already\nreserved.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_isActive", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::isActive", "doc": "&quot;Is the reservable set as active.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_setActive", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::setActive", "doc": "&quot;Set the active state for this reservable.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_clearMeta", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::clearMeta", "doc": "&quot;Clear a meta value from the database.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_isSingleDay", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::isSingleDay", "doc": "&quot;Is the reservable in singleday mode?&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_setSingleDay", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::setSingleDay", "doc": "&quot;Set the singleday mode value.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_setDisabledDaysAdminData", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::setDisabledDaysAdminData", "doc": "&quot;Set admin panel settings disabled days data.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_getDisabledDaysAdminData", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::getDisabledDaysAdminData", "doc": "&quot;Get data for admin disabled days.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_isLoginRequired", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::isLoginRequired", "doc": "&quot;Do reservations for this item require login?&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_setLoginRequired", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::setLoginRequired", "doc": "&quot;Set login requirement flag.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_isDayReserved", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::isDayReserved", "doc": "&quot;Is a day reserved for this reservable? Counts pending and accepted reservations.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_getReservationForDate", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::getReservationForDate", "doc": "&quot;Get reseravation for a date for this reservable.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_flushCache", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::flushCache", "doc": "&quot;Flush all cached data for this reservable. Loop through predefined cache keys and delete transients.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_calendarCacheFlushed", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::calendarCacheFlushed", "doc": "&quot;Should the calendar cache be flushed on next calendars load?&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_clearCalendarFlush", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::clearCalendarFlush", "doc": "&quot;Clear the calendar flush flag.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_hasReservations", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::hasReservations", "doc": "&quot;Does this reservable habe &lt;em&gt;any&lt;\/em&gt; reservations?&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservable", "fromLink": "Wprsrv/PostTypes/Objects/Reservable.html", "link": "Wprsrv/PostTypes/Objects/Reservable.html#method_hasReservationInDateRange", "name": "Wprsrv\\PostTypes\\Objects\\Reservable::hasReservationInDateRange", "doc": "&quot;Validate whether this reservable has either a pending or an accepted\nreservation between two dates.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\PostTypes\\Objects", "fromLink": "Wprsrv/PostTypes/Objects.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html", "name": "Wprsrv\\PostTypes\\Objects\\Reservation", "doc": "&quot;Class Reservation&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_create", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::create", "doc": "&quot;Create a new instance of Reservation from given data.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_getReservationStatus", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::getReservationStatus", "doc": "&quot;Return the reservation&#039;s post status.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_getReservable", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::getReservable", "doc": "&quot;Get the reservations reservable item ID.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_getReserverEmail", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::getReserverEmail", "doc": "&quot;Get the email address of the person who reserved.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_getStartDate", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::getStartDate", "doc": "&quot;Get starting timestamp.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_getEndDate", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::getEndDate", "doc": "&quot;Get ending timestamp.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_getEditLink", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::getEditLink", "doc": "&quot;Get the wp-admin editing URL link for this reservation.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_clearMeta", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::clearMeta", "doc": "&quot;Clear a meta value from the database.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_addNote", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::addNote", "doc": "&quot;Adds a short note about a reservation. Uses time() as the timestamp.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_getNotes", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::getNotes", "doc": "&quot;Get all notes attached to this reservation.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_accept", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::accept", "doc": "&quot;Accept this reservation.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_decline", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::decline", "doc": "&quot;Decline a reservation.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_isDeclined", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::isDeclined", "doc": "&quot;Is the reservation declined?&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_isPending", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::isPending", "doc": "&quot;Is the reservation pending?&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_isAccepted", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::isAccepted", "doc": "&quot;Is the reservation accepted?&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Objects\\Reservation", "fromLink": "Wprsrv/PostTypes/Objects/Reservation.html", "link": "Wprsrv/PostTypes/Objects/Reservation.html#method_containsDate", "name": "Wprsrv\\PostTypes\\Objects\\Reservation::containsDate", "doc": "&quot;Does this reservation contain a date within the reservation range.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\PostTypes", "fromLink": "Wprsrv/PostTypes.html", "link": "Wprsrv/PostTypes/PostType.html", "name": "Wprsrv\\PostTypes\\PostType", "doc": "&quot;Class PostType&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\PostTypes\\PostType", "fromLink": "Wprsrv/PostTypes/PostType.html", "link": "Wprsrv/PostTypes/PostType.html#method___construct", "name": "Wprsrv\\PostTypes\\PostType::__construct", "doc": "&quot;Constructor.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\PostTypes", "fromLink": "Wprsrv/PostTypes.html", "link": "Wprsrv/PostTypes/Reservable.html", "name": "Wprsrv\\PostTypes\\Reservable", "doc": "&quot;Class Reservable&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservable", "fromLink": "Wprsrv/PostTypes/Reservable.html", "link": "Wprsrv/PostTypes/Reservable.html#method_saveReservable", "name": "Wprsrv\\PostTypes\\Reservable::saveReservable", "doc": "&quot;Custom saving logic for reservables.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservable", "fromLink": "Wprsrv/PostTypes/Reservable.html", "link": "Wprsrv/PostTypes/Reservable.html#method_generateReservationForm", "name": "Wprsrv\\PostTypes\\Reservable::generateReservationForm", "doc": "&quot;Generate a reservation form object for a reservable.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservable", "fromLink": "Wprsrv/PostTypes/Reservable.html", "link": "Wprsrv/PostTypes/Reservable.html#method_editFormMetaBox", "name": "Wprsrv\\PostTypes\\Reservable::editFormMetaBox", "doc": "&quot;Spawn edit screen metaboxes for reservables.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv\\PostTypes", "fromLink": "Wprsrv/PostTypes.html", "link": "Wprsrv/PostTypes/Reservation.html", "name": "Wprsrv\\PostTypes\\Reservation", "doc": "&quot;Class Reservation&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_editFormMetaBox", "name": "Wprsrv\\PostTypes\\Reservation::editFormMetaBox", "doc": "&quot;Add and remove metaboxes in the edit screen.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_parseReservationAdminListFilters", "name": "Wprsrv\\PostTypes\\Reservation::parseReservationAdminListFilters", "doc": "&quot;Parse the filtering options for custom reservation list filters.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_reservationAdminFilters", "name": "Wprsrv\\PostTypes\\Reservation::reservationAdminFilters", "doc": "&quot;Custom filters for filtering the admin reservations list.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_customizeAdminColumns", "name": "Wprsrv\\PostTypes\\Reservation::customizeAdminColumns", "doc": "&quot;Custom columns for reservations.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_customizeAdminColumnContent", "name": "Wprsrv\\PostTypes\\Reservation::customizeAdminColumnContent", "doc": "&quot;Custom content for reservation custom columns.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_newPendingReservation", "name": "Wprsrv\\PostTypes\\Reservation::newPendingReservation", "doc": "&quot;Actions to run when new reservation is made.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_newAcceptedReservation", "name": "Wprsrv\\PostTypes\\Reservation::newAcceptedReservation", "doc": "&quot;Actions to run when a reservation is accepted.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_newDeclinedReservation", "name": "Wprsrv\\PostTypes\\Reservation::newDeclinedReservation", "doc": "&quot;Actions to run when a reservation is declined.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_emailSendFailed", "name": "Wprsrv\\PostTypes\\Reservation::emailSendFailed", "doc": "&quot;Handle failed email sends.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\PostTypes\\Reservation", "fromLink": "Wprsrv/PostTypes/Reservation.html", "link": "Wprsrv/PostTypes/Reservation.html#method_pruneReservations", "name": "Wprsrv\\PostTypes\\Reservation::pruneReservations", "doc": "&quot;Prune all reservations that have expired according to their &lt;code&gt;prune_date&lt;\/code&gt; meta\nvalue.&quot;"},
            
            {"type": "Class", "fromName": "Wprsrv", "fromLink": "Wprsrv.html", "link": "Wprsrv/Settings.html", "name": "Wprsrv\\Settings", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Settings", "fromLink": "Wprsrv/Settings.html", "link": "Wprsrv/Settings.html#method___construct", "name": "Wprsrv\\Settings::__construct", "doc": "&quot;Constructor.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Settings", "fromLink": "Wprsrv/Settings.html", "link": "Wprsrv/Settings.html#method___get", "name": "Wprsrv\\Settings::__get", "doc": "&quot;Magic settings getter.&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Settings", "fromLink": "Wprsrv/Settings.html", "link": "Wprsrv/Settings.html#method_getSettings", "name": "Wprsrv\\Settings::getSettings", "doc": "&quot;Get all settings.&quot;"},
            
            {"type": "Trait", "fromName": "Wprsrv\\Traits", "fromLink": "Wprsrv/Traits.html", "link": "Wprsrv/Traits/CastsToPost.html", "name": "Wprsrv\\Traits\\CastsToPost", "doc": "&quot;Trait CastsToPost&quot;"},
                                                        {"type": "Method", "fromName": "Wprsrv\\Traits\\CastsToPost", "fromLink": "Wprsrv/Traits/CastsToPost.html", "link": "Wprsrv/Traits/CastsToPost.html#method___get", "name": "Wprsrv\\Traits\\CastsToPost::__get", "doc": "&quot;Magic getter&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Traits\\CastsToPost", "fromLink": "Wprsrv/Traits/CastsToPost.html", "link": "Wprsrv/Traits/CastsToPost.html#method___set", "name": "Wprsrv\\Traits\\CastsToPost::__set", "doc": "&quot;Magic setter&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Traits\\CastsToPost", "fromLink": "Wprsrv/Traits/CastsToPost.html", "link": "Wprsrv/Traits/CastsToPost.html#method___call", "name": "Wprsrv\\Traits\\CastsToPost::__call", "doc": "&quot;Magic calls&quot;"},
                    {"type": "Method", "fromName": "Wprsrv\\Traits\\CastsToPost", "fromLink": "Wprsrv/Traits/CastsToPost.html", "link": "Wprsrv/Traits/CastsToPost.html#method___construct", "name": "Wprsrv\\Traits\\CastsToPost::__construct", "doc": "&quot;Constructor&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


