/** @format */

import React, { PureComponent } from "react";
import { Text, View, ScrollView } from "react-native";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { connect } from "react-redux";
import Tcomb from "tcomb-form-native";
import { cloneDeep } from "lodash";
import { TextInputMask } from "react-native-masked-text";
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
import CountryPicker from "react-native-country-picker-modal";

import Buttons from "@cart/Buttons";
import { Config, Validator, Languages, withTheme, Styles } from "@common";
import { toast, warn } from "@app/Omni";
import css from "@cart/styles";
import styles from "./styles";

const Form = Tcomb.form.Form;
const customInputStyle = cloneDeep(Tcomb.form.Form.stylesheet);

class Delivery extends PureComponent {
  constructor(props) {
    super(props);
    this.state = {
      value: {
        first_name: "",
        last_name: "",
        address_1: "",
        city: "",
        postcode: "",
        country: "",
        email: "",
        phone: "",
      },
      cca2: "VN",
    };
    this.initFormValues();
  }

  componentDidMount() {
    this.fetchCustomer(this.props);
  }

  componentWillReceiveProps(nextProps) {
    if (nextProps.user != this.props.user) {
      this.fetchCustomer(nextProps);
    }
  }

  _getCustomInputStyle = () => {
    const {
      theme: {
        colors: { text },
      },
    } = this.props;

    customInputStyle.controlLabel.normal = {
      ...customInputStyle.controlLabel.normal,
      fontSize: 15,
      color: text,
    };
    customInputStyle.textbox.normal = {
      ...customInputStyle.textbox.normal,
      color: text,
      width: Styles.width / 2,
    };
    customInputStyle.textbox.error = {
      ...customInputStyle.textbox.normal,
      color: text,
      width: Styles.width / 2,
    };
    customInputStyle.formGroup.normal = {
      ...customInputStyle.formGroup.normal,
      flexDirection: "row",
      flexWrap: "wrap",
      alignItems: "center",
      justifyContent: "space-between",
    };
    customInputStyle.formGroup.error = {
      ...customInputStyle.formGroup.normal,
      flexDirection: "row",
      flexWrap: "wrap",
      alignItems: "center",
      justifyContent: "space-between",
    };
    customInputStyle.errorBlock = {
      ...customInputStyle.errorBlock,
      width: Styles.width,
    };

    return customInputStyle;
  };

  onChange = (value) => this.setState({ value });

  onPress = () => this.refs.form.getValue();

  initFormValues = () => {
    const {
      theme: {
        colors: { placeholder },
      },
    } = this.props;
    // const countries = this.props.countries;
    // override the validate method of Tcomb lib for multi validate requirement.
    // const Countries = Tcomb.enums(countries);
    const Email = Tcomb.refinement(
      Tcomb.String,
      (s) => Validator.checkEmail(s) === undefined
    );
    Email.getValidationErrorMessage = (s) => Validator.checkEmail(s);
    const Phone = Tcomb.refinement(
      Tcomb.String,
      (s) => Validator.checkPhone(s) === undefined
    );
    Phone.getValidationErrorMessage = (s) => Validator.checkPhone(s);

    // define customer form
    this.Customer = Tcomb.struct({
      first_name: Tcomb.String,
      last_name: Tcomb.String,
      address_1: Tcomb.String,
      ...(Config.DefaultCountry.hideCountryList
        ? {}
        : { country: Tcomb.String }),
      city: Tcomb.String,
      postcode: Tcomb.String,
      email: Email,
      phone: Tcomb.String,
    });

    // form options
    this.options = {
      auto: "none", // we have labels and placeholders as option here (in Engrish, ofcourse).
      // stylesheet: css,
      fields: {
        first_name: {
          label: Languages.FirstName,
          placeholder: Languages.TypeFirstName,
          error: Languages.EmptyError, // for simple empty error warning.
          underlineColorAndroid: "transparent",
          stylesheet: this._getCustomInputStyle(),
          placeholderTextColor: placeholder,
        },
        last_name: {
          label: Languages.LastName,
          placeholder: Languages.TypeLastName,
          error: Languages.EmptyError,
          underlineColorAndroid: "transparent",
          stylesheet: this._getCustomInputStyle(),
          placeholderTextColor: placeholder,
        },
        address_1: {
          label: Languages.Address,
          placeholder: Languages.TypeAddress,
          error: Languages.EmptyError,
          underlineColorAndroid: "transparent",
          stylesheet: this._getCustomInputStyle(),
          placeholderTextColor: placeholder,
        },
        ...(Config.DefaultCountry.hideCountryList
          ? {}
          : {
            country: {
              label: Languages.TypeCountry,
              placeholder: Languages.Country,
              error: Languages.NotSelectedError,
              stylesheet: this._getCustomInputStyle(),
              template: this.renderCountry,
              placeholderTextColor: placeholder,
            },
          }),
        city: {
          label: Languages.City,
          placeholder: Languages.TypeCity,
          error: Languages.EmptyError,
          underlineColorAndroid: "transparent",
          stylesheet: this._getCustomInputStyle(),
          autoCorrect: false,
          placeholderTextColor: placeholder,
        },
        postcode: {
          label: Languages.Postcode,
          placeholder: Languages.TypePostcode,
          error: Languages.EmptyError,
          underlineColorAndroid: "transparent",
          stylesheet: this._getCustomInputStyle(),
          autoCorrect: false,
          placeholderTextColor: placeholder,
        },
        email: {
          label: Languages.Email,
          placeholder: Languages.TypeEmail,
          underlineColorAndroid: "transparent",
          stylesheet: this._getCustomInputStyle(),
          autoCorrect: false,
          placeholderTextColor: placeholder,
        },
        phone: {
          label: Languages.Phone,
          placeholder: Languages.TypePhone,
          underlineColorAndroid: "transparent",
          error: Languages.EmptyError,
          stylesheet: this._getCustomInputStyle(),
          template: this.renderPhoneInput,
          autoCorrect: false,
          placeholderTextColor: placeholder,
        }
      },
    };
  };

  renderPhoneInput = (locals) => {
    const {
      theme: {
        colors: { placeholder },
      },
    } = this.props;
    const stylesheet = locals.stylesheet;
    let formGroupStyle = stylesheet.formGroup.normal;
    let controlLabelStyle = stylesheet.controlLabel.normal;
    let textboxStyle = stylesheet.textbox.normal;
    let helpBlockStyle = stylesheet.helpBlock.normal;
    const errorBlockStyle = stylesheet.errorBlock;

    if (locals.hasError) {
      formGroupStyle = stylesheet.formGroup.error;
      controlLabelStyle = stylesheet.controlLabel.error;
      textboxStyle = stylesheet.textbox.error;
      helpBlockStyle = stylesheet.helpBlock.error;
    }

    const label = locals.label ? (
      <Text style={controlLabelStyle}>{locals.label}</Text>
    ) : null;
    const help = locals.help ? (
      <Text style={helpBlockStyle}>{locals.help}</Text>
    ) : null;
    const error =
      locals.hasError && locals.error ? (
        <Text accessibilityLiveRegion="polite" style={errorBlockStyle}>
          {locals.error}
        </Text>
      ) : null;

    return (
      <View style={formGroupStyle}>
        {label}
        <TextInputMask
          type="cel-phone"
          style={textboxStyle}
          onChangeText={(value) => locals.onChange(value)}
          onChange={locals.onChangeNative}
          placeholder={locals.placeholder}
          value={locals.value}
          placeholderTextColor={placeholder}
        />
        {help}
        {error}
      </View>
    );
  };

  renderCountry = (locals) => {
    const {
      theme: {
        colors: { placeholder },
      },
    } = this.props;
    const stylesheet = locals.stylesheet;
    let formGroupStyle = stylesheet.formGroup.normal;
    let controlLabelStyle = stylesheet.controlLabel.normal;
    let textboxStyle = stylesheet.textbox.normal;
    let helpBlockStyle = stylesheet.helpBlock.normal;
    const errorBlockStyle = stylesheet.errorBlock;

    if (locals.hasError) {
      formGroupStyle = stylesheet.formGroup.error;
      controlLabelStyle = stylesheet.controlLabel.error;
      textboxStyle = stylesheet.textbox.error;
      helpBlockStyle = stylesheet.helpBlock.error;
    }

    const label = locals.label ? (
      <Text style={controlLabelStyle}>{locals.label}</Text>
    ) : null;
    const help = locals.help ? (
      <Text style={helpBlockStyle}>{locals.help}</Text>
    ) : null;
    const error =
      locals.hasError && locals.error ? (
        <Text accessibilityLiveRegion="polite" style={errorBlockStyle}>
          {locals.error}
        </Text>
      ) : null;

    return (
      <View style={formGroupStyle}>
        {label}
        <CountryPicker
          onSelect={(value) => {
            this.setState({ cca2: value.cca2 });
            locals.onChange(value.name);
          }}
          countryCode={this.state.cca2}
          filterable>
          <Text
            style={[
              textboxStyle,
              locals.value == "" && { color: placeholder },
            ]}>
            {locals.value == "" ? locals.placeholder : locals.value}
          </Text>
        </CountryPicker>
        {help}
        {error}
      </View>
    );
  };

  fetchCustomer = async (props) => {
    const { selectedAddress } = props;
    const { user: customer } = props.user;

    let value = selectedAddress;
    if (!selectedAddress && customer) {
      value = {
        first_name:
          customer.billing.first_name == ""
            ? customer.first_name
            : customer.billing.first_name,
        last_name:
          customer.billing.last_name == ""
            ? customer.last_name
            : customer.billing.last_name,
        email:
          customer.email.first_name == ""
            ? customer.email
            : customer.billing.email,
        address_1: customer.billing.address_1,
        city: customer.billing.city,
        state: customer.billing.state,
        postcode: customer.billing.postcode,
        country: customer.billing.country,
        phone: customer.billing.phone,
      };
    }

    this.setState({ value });
  };

  validateCustomer = async (customerInfo) => {
    await this.props.validateCustomerInfo(customerInfo);
    if (this.props.type === "INVALIDATE_CUSTOMER_INFO") {
      toast(this.props.message);
      return false;
    }
    this.props.onNext();
  };

  saveUserData = async (userInfo) => {
    this.props.updateSelectedAddress(userInfo);
    try {
      await AsyncStorage.setItem("@userInfo", JSON.stringify(userInfo));
    } catch (error) {

    }
  };

  nextStep = () => {
    const value = this.refs.form.getValue();
    if (value) {
      let country = "";
      if (Config.DefaultCountry.hideCountryList) {
        country = Config.DefaultCountry.countryCode.toUpperCase();
      } else {
        // Woocommerce only using cca2 to checkout
        country = this.state.cca2;
      }

      // if validation fails, value will be null
      this.props.onNext({ ...this.state.value, country });

      // save user info for next use
      this.saveUserData({ ...this.state.value, country });

      //set billing address for magento 2
      this.props.updateBillingCustomer({ ...this.state.value, country })
    }
  };

  render() {
    const {
      theme: {
        colors: { text },
      },
    } = this.props;

    return (
      <View style={styles.container}>
        <KeyboardAwareScrollView style={styles.form} enableOnAndroid>
          <View style={css.rowEmpty}>
            <Text style={[css.label, { color: text }]}>
              {Languages.YourDeliveryInfo}
            </Text>
          </View>

          <View style={styles.formContainer}>
            <Form
              ref="form"
              type={this.Customer}
              options={this.options}
              value={this.state.value}
              onChange={this.onChange}
            />
          </View>
        </KeyboardAwareScrollView>

        <Buttons
          isAbsolute
          onPrevious={this.props.onPrevious}
          onNext={this.nextStep}
        />
      </View>
    );
  }
}

Delivery.defaultProps = {
  selectedAddress: {},
};

const mapStateToProps = ({ carts, user, countries, addresses }) => {
  return {
    user,
    customerInfo: carts.customerInfo,
    message: carts.message,
    type: carts.type,
    countries: countries.list,
    selectedAddress: addresses.selectedAddress,
  };
};

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { dispatch } = dispatchProps;
  const CartRedux = require("@redux/CartRedux");
  const AddressRedux = require("@redux/AddressRedux");
  const UserRedux = require("@redux/UserRedux");

  return {
    ...ownProps,
    ...stateProps,
    validateCustomerInfo: (customerInfo) => {
      CartRedux.actions.validateCustomerInfo(dispatch, customerInfo);
    },
    updateSelectedAddress: (address) => {
      AddressRedux.actions.updateSelectedAddress(dispatch, address);
    },
    updateBillingCustomer: (address) => {
      dispatch(UserRedux.actions.updateBillingCustomer(address));
    },
  };
}

export default connect(
  mapStateToProps,
  undefined,
  mergeProps
)(withTheme(Delivery));
