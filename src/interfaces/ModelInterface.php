<?php


namespace vasadibt\user\interfaces;

use ArrayAccess;
use ArrayObject;
use IteratorAggregate;
use yii\base\Arrayable;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\StaticInstanceInterface;

/**
 * Interface ModelInterface
 * @package vasadibt\user\interfaces
 */
interface ModelInterface extends StaticInstanceInterface, IteratorAggregate, ArrayAccess, Arrayable
{
    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * Each rule is an array with the following structure:
     *
     * ```php
     * [
     *     ['attribute1', 'attribute2'],
     *     'validator type',
     *     'on' => ['scenario1', 'scenario2'],
     *     //...other parameters...
     * ]
     * ```
     *
     * where
     *
     *  - attribute list: required, specifies the attributes array to be validated, for single attribute you can pass a string;
     *  - validator type: required, specifies the validator to be used. It can be a built-in validator name,
     *    a method name of the model class, an anonymous function, or a validator class name.
     *  - on: optional, specifies the [[scenario|scenarios]] array in which the validation
     *    rule can be applied. If this option is not set, the rule will apply to all scenarios.
     *  - additional name-value pairs can be specified to initialize the corresponding validator properties.
     *    Please refer to individual validator class API for possible properties.
     *
     * A validator can be either an object of a class extending [[Validator]], or a model class method
     * (called *inline validator*) that has the following signature:
     *
     * ```php
     * // $params refers to validation parameters given in the rule
     * function validatorName($attribute, $params)
     * ```
     *
     * In the above `$attribute` refers to the attribute currently being validated while `$params` contains an array of
     * validator configuration options such as `max` in case of `string` validator. The value of the attribute currently being validated
     * can be accessed as `$this->$attribute`. Note the `$` before `attribute`; this is taking the value of the variable
     * `$attribute` and using it as the name of the property to access.
     *
     * Yii also provides a set of [[Validator::builtInValidators|built-in validators]].
     * Each one has an alias name which can be used when specifying a validation rule.
     *
     * Below are some examples:
     *
     * ```php
     * [
     *     // built-in "required" validator
     *     [['username', 'password'], 'required'],
     *     // built-in "string" validator customized with "min" and "max" properties
     *     ['username', 'string', 'min' => 3, 'max' => 12],
     *     // built-in "compare" validator that is used in "register" scenario only
     *     ['password', 'compare', 'compareAttribute' => 'password2', 'on' => 'register'],
     *     // an inline validator defined via the "authenticate()" method in the model class
     *     ['password', 'authenticate', 'on' => 'login'],
     *     // a validator of class "DateRangeValidator"
     *     ['dateRange', 'DateRangeValidator'],
     * ];
     * ```
     *
     * Note, in order to inherit rules defined in the parent class, a child class needs to
     * merge the parent rules with child rules using functions such as `array_merge()`.
     *
     * @return array validation rules
     * @see scenarios()
     */
    public function rules();

    /**
     * Returns a list of scenarios and the corresponding active attributes.
     *
     * An active attribute is one that is subject to validation in the current scenario.
     * The returned array should be in the following format:
     *
     * ```php
     * [
     *     'scenario1' => ['attribute11', 'attribute12', ...],
     *     'scenario2' => ['attribute21', 'attribute22', ...],
     *     ...
     * ]
     * ```
     *
     * By default, an active attribute is considered safe and can be massively assigned.
     * If an attribute should NOT be massively assigned (thus considered unsafe),
     * please prefix the attribute with an exclamation character (e.g. `'!rank'`).
     *
     * The default implementation of this method will return all scenarios found in the [[rules()]]
     * declaration. A special scenario named [[SCENARIO_DEFAULT]] will contain all attributes
     * found in the [[rules()]]. Each scenario will be associated with the attributes that
     * are being validated by the validation rules that apply to the scenario.
     *
     * @return array a list of scenarios and the corresponding active attributes.
     */
    public function scenarios();

    /**
     * Returns the form name that this model class should use.
     *
     * The form name is mainly used by [[\yii\widgets\ActiveForm]] to determine how to name
     * the input fields for the attributes in a model. If the form name is "A" and an attribute
     * name is "b", then the corresponding input name would be "A[b]". If the form name is
     * an empty string, then the input name would be "b".
     *
     * The purpose of the above naming schema is that for forms which contain multiple different models,
     * the attributes of each model are grouped in sub-arrays of the POST-data and it is easier to
     * differentiate between them.
     *
     * By default, this method returns the model class name (without the namespace part)
     * as the form name. You may override it when the model is used in different forms.
     *
     * @return string the form name of this model class.
     * @throws InvalidConfigException when form is defined with anonymous class and `formName()` method is
     * not overridden.
     * @see load()
     */
    public function formName();

    /**
     * Returns the list of attribute names.
     * By default, this method returns all public non-static properties of the class.
     * You may override this method to change the default behavior.
     * @return array list of attribute names.
     */
    public function attributes();

    /**
     * Returns the attribute labels.
     *
     * Attribute labels are mainly used for display purpose. For example, given an attribute
     * `firstName`, we can declare a label `First Name` which is more user-friendly and can
     * be displayed to end users.
     *
     * By default an attribute label is generated using [[generateAttributeLabel()]].
     * This method allows you to explicitly specify attribute labels.
     *
     * Note, in order to inherit labels defined in the parent class, a child class needs to
     * merge the parent labels with child labels using functions such as `array_merge()`.
     *
     * @return array attribute labels (name => label)
     * @see generateAttributeLabel()
     */
    public function attributeLabels();

    /**
     * Returns the attribute hints.
     *
     * Attribute hints are mainly used for display purpose. For example, given an attribute
     * `isPublic`, we can declare a hint `Whether the post should be visible for not logged in users`,
     * which provides user-friendly description of the attribute meaning and can be displayed to end users.
     *
     * Unlike label hint will not be generated, if its explicit declaration is omitted.
     *
     * Note, in order to inherit hints defined in the parent class, a child class needs to
     * merge the parent hints with child hints using functions such as `array_merge()`.
     *
     * @return array attribute hints (name => hint)
     * @since 2.0.4
     */
    public function attributeHints();

    /**
     * Performs the data validation.
     *
     * This method executes the validation rules applicable to the current [[scenario]].
     * The following criteria are used to determine whether a rule is currently applicable:
     *
     * - the rule must be associated with the attributes relevant to the current scenario;
     * - the rules must be effective for the current scenario.
     *
     * This method will call [[beforeValidate()]] and [[afterValidate()]] before and
     * after the actual validation, respectively. If [[beforeValidate()]] returns false,
     * the validation will be cancelled and [[afterValidate()]] will not be called.
     *
     * Errors found during the validation can be retrieved via [[getErrors()]],
     * [[getFirstErrors()]] and [[getFirstError()]].
     *
     * @param string[]|string $attributeNames attribute name or list of attribute names that should be validated.
     * If this parameter is empty, it means any attribute listed in the applicable
     * validation rules should be validated.
     * @param bool $clearErrors whether to call [[clearErrors()]] before performing validation
     * @return bool whether the validation is successful without any error.
     * @throws InvalidArgumentException if the current scenario is unknown.
     */
    public function validate($attributeNames = null, $clearErrors = true);

    /**
     * This method is invoked before validation starts.
     * The default implementation raises a `beforeValidate` event.
     * You may override this method to do preliminary checks before validation.
     * Make sure the parent implementation is invoked so that the event can be raised.
     * @return bool whether the validation should be executed. Defaults to true.
     * If false is returned, the validation will stop and the model is considered invalid.
     */
    public function beforeValidate();

    /**
     * This method is invoked after validation ends.
     * The default implementation raises an `afterValidate` event.
     * You may override this method to do postprocessing after validation.
     * Make sure the parent implementation is invoked so that the event can be raised.
     */
    public function afterValidate();

    /**
     * Returns all the validators declared in [[rules()]].
     *
     * This method differs from [[getActiveValidators()]] in that the latter
     * only returns the validators applicable to the current [[scenario]].
     *
     * Because this method returns an ArrayObject object, you may
     * manipulate it by inserting or removing validators (useful in model behaviors).
     * For example,
     *
     * ```php
     * $model->validators[] = $newValidator;
     * ```
     *
     * @return ArrayObject|\yii\validators\Validator[] all the validators declared in the model.
     */
    public function getValidators();

    /**
     * Returns the validators applicable to the current [[scenario]].
     * @param string $attribute the name of the attribute whose applicable validators should be returned.
     * If this is null, the validators for ALL attributes in the model will be returned.
     * @return \yii\validators\Validator[] the validators applicable to the current [[scenario]].
     */
    public function getActiveValidators($attribute = null);

    /**
     * Creates validator objects based on the validation rules specified in [[rules()]].
     * Unlike [[getValidators()]], each time this method is called, a new list of validators will be returned.
     * @return ArrayObject validators
     * @throws InvalidConfigException if any validation rule configuration is invalid
     */
    public function createValidators();

    /**
     * Returns a value indicating whether the attribute is required.
     * This is determined by checking if the attribute is associated with a
     * [[\yii\validators\RequiredValidator|required]] validation rule in the
     * current [[scenario]].
     *
     * Note that when the validator has a conditional validation applied using
     * [[\yii\validators\RequiredValidator::$when|$when]] this method will return
     * `false` regardless of the `when` condition because it may be called be
     * before the model is loaded with data.
     *
     * @param string $attribute attribute name
     * @return bool whether the attribute is required
     */
    public function isAttributeRequired($attribute);

    /**
     * Returns a value indicating whether the attribute is safe for massive assignments.
     * @param string $attribute attribute name
     * @return bool whether the attribute is safe for massive assignments
     * @see safeAttributes()
     */
    public function isAttributeSafe($attribute);

    /**
     * Returns a value indicating whether the attribute is active in the current scenario.
     * @param string $attribute attribute name
     * @return bool whether the attribute is active in the current scenario
     * @see activeAttributes()
     */
    public function isAttributeActive($attribute);

    /**
     * Returns the text label for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute label
     * @see generateAttributeLabel()
     * @see attributeLabels()
     */
    public function getAttributeLabel($attribute);

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     * @see attributeHints()
     * @since 2.0.4
     */
    public function getAttributeHint($attribute);

    /**
     * Returns a value indicating whether there is any validation error.
     * @param string|null $attribute attribute name. Use null to check all attributes.
     * @return bool whether there is any error.
     */
    public function hasErrors($attribute = null);

    /**
     * Returns the errors for all attributes or a single attribute.
     * @param string $attribute attribute name. Use null to retrieve errors for all attributes.
     * @return array errors for all attributes or the specified attribute. Empty array is returned if no error.
     * Note that when returning errors for all attributes, the result is a two-dimensional array, like the following:
     *
     * ```php
     * [
     *     'username' => [
     *         'Username is required.',
     *         'Username must contain only word characters.',
     *     ],
     *     'email' => [
     *         'Email address is invalid.',
     *     ]
     * ]
     * ```
     *
     * @property array An array of errors for all attributes. Empty array is returned if no error.
     * The result is a two-dimensional array. See [[getErrors()]] for detailed description.
     * @see getFirstErrors()
     * @see getFirstError()
     */
    public function getErrors($attribute = null);

    /**
     * Returns the first error of every attribute in the model.
     * @return array the first errors. The array keys are the attribute names, and the array
     * values are the corresponding error messages. An empty array will be returned if there is no error.
     * @see getErrors()
     * @see getFirstError()
     */
    public function getFirstErrors();

    /**
     * Returns the first error of the specified attribute.
     * @param string $attribute attribute name.
     * @return string|null the error message. Null is returned if no error.
     * @see getErrors()
     * @see getFirstErrors()
     */
    public function getFirstError($attribute);

    /**
     * Returns the errors for all attributes as a one-dimensional array.
     * @param bool $showAllErrors boolean, if set to true every error message for each attribute will be shown otherwise
     * only the first error message for each attribute will be shown.
     * @return array errors for all attributes as a one-dimensional array. Empty array is returned if no error.
     * @see getErrors()
     * @see getFirstErrors()
     * @since 2.0.14
     */
    public function getErrorSummary($showAllErrors);

    /**
     * Adds a new error to the specified attribute.
     * @param string $attribute attribute name
     * @param string $error new error message
     */
    public function addError($attribute, $error = '');

    /**
     * Adds a list of errors.
     * @param array $items a list of errors. The array keys must be attribute names.
     * The array values should be error messages. If an attribute has multiple errors,
     * these errors must be given in terms of an array.
     * You may use the result of [[getErrors()]] as the value for this parameter.
     * @since 2.0.2
     */
    public function addErrors(array $items);

    /**
     * Removes errors for all attributes or a single attribute.
     * @param string $attribute attribute name. Use null to remove errors for all attributes.
     */
    public function clearErrors($attribute = null);

    /**
     * Generates a user friendly attribute label based on the give attribute name.
     * This is done by replacing underscores, dashes and dots with blanks and
     * changing the first letter of each word to upper case.
     * For example, 'department_name' or 'DepartmentName' will generate 'Department Name'.
     * @param string $name the column name
     * @return string the attribute label
     */
    public function generateAttributeLabel($name);

    /**
     * Returns attribute values.
     * @param array $names list of attributes whose value needs to be returned.
     * Defaults to null, meaning all attributes listed in [[attributes()]] will be returned.
     * If it is an array, only the attributes in the array will be returned.
     * @param array $except list of attributes whose value should NOT be returned.
     * @return array attribute values (name => value).
     */
    public function getAttributes($names = null, $except = []);

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name => value) to be assigned to the model.
     * @param bool $safeOnly whether the assignments should only be done to the safe attributes.
     * A safe attribute is one that is associated with a validation rule in the current [[scenario]].
     * @see safeAttributes()
     * @see attributes()
     */
    public function setAttributes($values, $safeOnly = true);

    /**
     * This method is invoked when an unsafe attribute is being massively assigned.
     * The default implementation will log a warning message if YII_DEBUG is on.
     * It does nothing otherwise.
     * @param string $name the unsafe attribute name
     * @param mixed $value the attribute value
     */
    public function onUnsafeAttribute($name, $value);

    /**
     * Returns the scenario that this model is used in.
     *
     * Scenario affects how validation is performed and which attributes can
     * be massively assigned.
     *
     * @return string the scenario that this model is in. Defaults to [[SCENARIO_DEFAULT]].
     */
    public function getScenario();

    /**
     * Sets the scenario for the model.
     * Note that this method does not check if the scenario exists or not.
     * The method [[validate()]] will perform this check.
     * @param string $value the scenario that this model is in.
     */
    public function setScenario($value);

    /**
     * Returns the attribute names that are safe to be massively assigned in the current scenario.
     * @return string[] safe attribute names
     */
    public function safeAttributes();

    /**
     * Returns the attribute names that are subject to validation in the current scenario.
     * @return string[] safe attribute names
     */
    public function activeAttributes();

    /**
     * Populates the model with input data.
     *
     * This method provides a convenient shortcut for:
     *
     * ```php
     * if (isset($_POST['FormName'])) {
     *     $model->attributes = $_POST['FormName'];
     *     if ($model->save()) {
     *         // handle success
     *     }
     * }
     * ```
     *
     * which, with `load()` can be written as:
     *
     * ```php
     * if ($model->load($_POST) && $model->save()) {
     *     // handle success
     * }
     * ```
     *
     * `load()` gets the `'FormName'` from the model's [[formName()]] method (which you may override), unless the
     * `$formName` parameter is given. If the form name is empty, `load()` populates the model with the whole of `$data`,
     * instead of `$data['FormName']`.
     *
     * Note, that the data being populated is subject to the safety check by [[setAttributes()]].
     *
     * @param array $data the data array to load, typically `$_POST` or `$_GET`.
     * @param string $formName the form name to use to load the data into the model.
     * If not set, [[formName()]] is used.
     * @return bool whether `load()` found the expected form in `$data`.
     */
    public function load($data, $formName = null);

    /**
     * Populates a set of models with the data from end user.
     * This method is mainly used to collect tabular data input.
     * The data to be loaded for each model is `$data[formName][index]`, where `formName`
     * refers to the value of [[formName()]], and `index` the index of the model in the `$models` array.
     * If [[formName()]] is empty, `$data[index]` will be used to populate each model.
     * The data being populated to each model is subject to the safety check by [[setAttributes()]].
     * @param array $models the models to be populated. Note that all models should have the same class.
     * @param array $data the data array. This is usually `$_POST` or `$_GET`, but can also be any valid array
     * supplied by end user.
     * @param string $formName the form name to be used for loading the data into the models.
     * If not set, it will use the [[formName()]] value of the first model in `$models`.
     * This parameter is available since version 2.0.1.
     * @return bool whether at least one of the models is successfully populated.
     */
    public static function loadMultiple($models, $data, $formName = null);

    /**
     * Validates multiple models.
     * This method will validate every model. The models being validated may
     * be of the same or different types.
     * @param array $models the models to be validated
     * @param array $attributeNames list of attribute names that should be validated.
     * If this parameter is empty, it means any attribute listed in the applicable
     * validation rules should be validated.
     * @return bool whether all models are valid. False will be returned if one
     * or multiple models have validation error.
     */
    public static function validateMultiple($models, $attributeNames = null);
}