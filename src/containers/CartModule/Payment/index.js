/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { Text, Dimensions, ScrollView, View } from "react-native";
import css from "@cart/styles";
import { connect } from "react-redux";
import { warn, toast } from "@app/Omni";
import { Button, ConfirmCheckout, ShippingMethod } from "@components";
import { Languages, Config, Images, withTheme } from "@common";
import Buttons from "@cart/Buttons";
import HTML from "react-native-render-html";
import styles from "./styles";
import * as OpencartWorker from '@services/OpencartWorker'
const { width } = Dimensions.get("window");

class PaymentOptions extends PureComponent {
  static propTypes = {
    message: PropTypes.array,
    type: PropTypes.string,
    cleanOldCoupon: PropTypes.func,
    onNext: PropTypes.func,
    user: PropTypes.object,
    userInfo: PropTypes.object,
    currency: PropTypes.any,
    payments: PropTypes.object,
    isLoading: PropTypes.bool,
    cartItems: PropTypes.any,
    onShowCheckOut: PropTypes.func,
    emptyCart: PropTypes.func,
    couponCode: PropTypes.any,
    couponId: PropTypes.any,
    couponAmount: PropTypes.any,
    shippingMethod: PropTypes.any,
  };

  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      selectedIndex: 0,
    };
  }


  componentWillReceiveProps(nextProps) {
    if (nextProps.message && nextProps.message.length > 0) {
      toast(nextProps.message);
    }

    if (
      nextProps.type !== this.props.type &&
      nextProps.type == "CREATE_NEW_ORDER_SUCCESS"
    ) {
      this.props.cleanOldCoupon();
      this.props.onNext();
    }

    if (nextProps.shippings && nextProps.shippings.length > 0 && (!this.props.shippingMethod || Object.keys(this.props.shippingMethod).length == 0)) {
      this.selectShippingMethod(nextProps.shippings[0]);
    }
  }

  selectShippingMethod = (item) => {
    this.props.selectShippingMethod(item);
  };

  nextStep = () => {
    const { list } = this.props.payments;
    const { shippingMethod } = this.props;
    const payload = {
      payment_method: list[this.state.selectedIndex].id,
      shipping_method: shippingMethod.method_id
    };

    this.setState({ loading: this.props.isLoading });

    if (list[this.state.selectedIndex].id == "cod" || list[this.state.selectedIndex].id == "free_checkout") {
      this.setState({ loading: true });
      OpencartWorker.createNewOrder(
        payload,
        () => {
          this.setState({ loading: false });
          this.props.emptyCart();
          this.props.onNext();
        },
        (message) => {
          alert(message)
          this.setState({ loading: false });
        }
      );
    } else {
      alert("The app only supports cod. It doesn't support "+list[this.state.selectedIndex].id)
      // other kind of payment
      //this.props.onShowCheckOut(payload);
    }
  };


  getShippingMethod = () => {
    const { shippingMethod } = this.props;
    if (shippingMethod) {
      return [
        {
          method_id: `${shippingMethod.method_id}`,
          method_title: shippingMethod.title,
          total:
            shippingMethod.id == "freeshipping" ||
              shippingMethod.method_id == "freeshipping"
              ? "0"
              : shippingMethod.settings.cost.value,
        },
      ];
    }
    // return the free class as default
    return [
      {
        method_id: "freeshipping",
        total: "0",
      },
    ];
  };

  renderDesLayout = (item) => {
    if (typeof item === "undefined") {
      return <View />;
    }
    if (item.description == null || item.description == "") return <View />;

    const tagsStyles = {
      p: {
        color: "#666",
        flex: 1,
        textAlign: "center",
        width: width - 40,
        paddingLeft: 20,
      },
    };
    return (
      <View style={styles.descriptionView}>
        <HTML tagsStyles={tagsStyles} html={`<p>${item.description}</p>`} />
      </View>
    );
  };

  render() {
    const { list } = this.props.payments;
    const {
      theme: {
        colors: { text },
      },
    } = this.props;
    const { shippings, shippingMethod } = this.props;
    const isShippingEmpty = !shippingMethod || !shippingMethod.id;

    return (
      <View style={styles.container}>
        <ScrollView >
          {Config.shipping.visible && shippings && shippings.length > 0 && (
            <View>
              <View style={css.rowEmpty}>
                <Text style={[css.label, { color: text }]}>
                  {Languages.ShippingType}
                </Text>
              </View>

              <ScrollView contentContainerStyle={styles.shippingMethod}>
                {shippings.map((item, index) => (
                  <ShippingMethod
                    item={item}
                    key={`${index}shipping`}
                    onPress={this.selectShippingMethod}
                    selected={
                      (index == 0 && isShippingEmpty) ||
                      item.id == shippingMethod.id
                    }
                  />
                ))}
              </ScrollView>
            </View>
          )}
          <View style={css.rowEmpty}>
            <Text style={[styles.label, { color: text }]}>
              {Languages.SelectPayment}:
            </Text>
          </View>

          <View style={styles.paymentOption}>
            {list.map((item, index) => {
              if (!item.enabled) return null;

              const image =
                typeof Config.Payments[item.id] !== "undefined" &&
                Config.Payments[item.id];
              return (
                <View style={styles.optionContainer} key={index.toString()}>
                  <Button
                    type="image"
                    source={image}
                    defaultSource={Images.defaultPayment}
                    onPress={() => this.setState({ selectedIndex: index })}
                    buttonStyle={[
                      styles.btnOption,
                      this.state.selectedIndex == index &&
                      styles.selectedBtnOption,
                    ]}
                    imageStyle={styles.imgOption}
                  />
                </View>
              );
            })}
          </View>
          {this.renderDesLayout(list[this.state.selectedIndex])}

          <ConfirmCheckout
            couponAmount={this.props.couponAmount}
            discountType={this.props.discountType}
            shippingMethod={this.getShippingMethod()}
            totalPrice={this.props.totalPrice}
          />
        </ScrollView>

        <Buttons
          isAbsolute
          onPrevious={this.props.onPrevious}
          isLoading={this.state.loading}
          nextText={Languages.ConfirmOrder}
          onNext={this.nextStep}
        />
      </View>
    );
  }
}

const mapStateToProps = ({ payments, carts, user, products, currency }) => {
  return {
    payments,
    user,
    type: carts.type,
    cartItems: carts.cartItems,
    totalPrice: carts.totalPrice,
    message: carts.message,
    customerInfo: carts.customerInfo,

    couponCode: products.coupon && products.coupon.code,
    couponAmount: products.coupon && products.coupon.amount,
    discountType: products.coupon && products.coupon.type,
    couponId: products.coupon && products.coupon.id,

    shippingMethod: carts.shippingMethod,
    shippings: carts.shippings,

    currency,
  };
};

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { dispatch } = dispatchProps;
  const CartRedux = require("@redux/CartRedux");
  const productActions = require("@redux/ProductRedux").actions;
  const paymentActions = require("@redux/PaymentRedux").actions;
  return {
    ...ownProps,
    ...stateProps,
    emptyCart: () => CartRedux.actions.emptyCart(dispatch),
    createNewOrder: (payload) => {
      CartRedux.actions.createNewOrder(dispatch, payload);
    },
    cleanOldCoupon: () => {
      productActions.cleanOldCoupon(dispatch);
    },
    selectShippingMethod: (shippingMethod) => {
      CartRedux.actions.selectShippingMethod(dispatch, shippingMethod);
    },
  };
}

export default connect(
  mapStateToProps,
  undefined,
  mergeProps
)(withTheme(PaymentOptions));
