/** @format */

import React, { PureComponent } from "react";
import {
  Text,
  View,
  ScrollView,
  TouchableOpacity,
  TextInput,
} from "react-native";
import { connect } from "react-redux";
import { SwipeRow } from "react-native-swipe-list-view";
import FontAwesome from "@expo/vector-icons/FontAwesome";
import { LinearGradient } from "@expo";

import { toast } from "@app/Omni";
import { ProductItem } from "@components";
import { Languages, Color, withTheme, Tools } from "@common";
import css from "@cart/styles";
import styles from "./styles";

class MyCart extends PureComponent {
  constructor(props) {
    super(props);

    this.state = {
      coupon: props.couponCode,
    };
  }

  componentWillReceiveProps(nextProps) {
    if (
      nextProps.hasOwnProperty("type") &&
      nextProps.type == "GET_COUPON_CODE_FAIL"
    ) {
      toast(nextProps.message);
    }
  }

  render() {
    const { cartItems, totalPrice, isFetching, discountType } = this.props;
    const {
      theme: {
        colors: { text, lineColor },
        dark,
      },
    } = this.props;

    let couponBtn = Languages.ApplyCoupon;
    let colors = [Color.darkOrange, Color.darkYellow, Color.yellow];
    const finalPrice =
      discountType == "percent"
        ? totalPrice - this.getExistCoupon() * totalPrice
        : totalPrice - this.getExistCoupon();

    if (isFetching) {
      couponBtn = Languages.ApplyCoupon;
    } else if (this.getExistCoupon() > 0) {
      colors = [Color.darkRed, Color.red];
      couponBtn = Languages.remove;
    }

    return (
      <View style={styles.container}>
        <ScrollView>
          <View style={css.row}>
            <Text style={[css.label, { color: text }]}>
              {Languages.TotalPrice}
            </Text>
            <Text style={css.value}>
              {Tools.getCurrecyFormatted(finalPrice)}
            </Text>
          </View>
          <View style={styles.list}>
            {cartItems &&
              cartItems.map((item, index) => (
                <SwipeRow
                  key={index.toString()}
                  disableRightSwipe
                  leftOpenValue={75}
                  rightOpenValue={-75}>
                  {this.renderHiddenRow(item, index)}
                  <ProductItem
                    key={index.toString()}
                    viewQuantity
                    product={item.product}
                    onPress={() =>
                      this.props.onViewProduct({ product: item.product })
                    }
                    variation={item.variation}
                    quantity={item.quantity}
                    onRemove={this.props.removeCartItem}
                  />
                </SwipeRow>
              ))}
          </View>
          <View style={[styles.couponView(dark)]}>
            <Text style={[css.label, { color: text }]}>
              {Languages.CouponPlaceholder}:
            </Text>
            <View style={styles.row}>
              <TextInput
                value={this.state.coupon}
                onChangeText={(coupon) => this.setState({ coupon })}
                style={[
                  styles.couponInput,
                  { backgroundColor: lineColor },
                  { color: text },
                  this.getExistCoupon() > 0 && {
                    backgroundColor: Color.lightgrey,
                  },
                ]}
                underlineColorAndroid="transparent"
                autoCapitalize="none"
                editable={this.getExistCoupon() == 0}
              />

              <TouchableOpacity
                activeOpacity={0.6}
                onPress={() => this.checkCouponCode()}
                disabled={this.state.coupon.length === 0}>
                <LinearGradient colors={colors} style={styles.btnApply}>
                  <Text style={styles.btnApplyText}>{couponBtn}</Text>
                </LinearGradient>
              </TouchableOpacity>
            </View>
            {this.getExistCoupon() > 0 && (
              <Text style={styles.couponMessage}>
                {Languages.applyCouponSuccess + this.getCouponString()}
              </Text>
            )}
          </View>
        </ScrollView>
      </View>
    );
  }

  renderHiddenRow = (rowData, index) => {
    return (
      <TouchableOpacity
        key={`hiddenRow-${index}`}
        style={styles.hiddenRow}
        onPress={() =>
          this.props.removeCartItem(rowData.product, rowData.variation)
        }>
        <View style={{ marginRight: 23 }}>
          <FontAwesome name="trash" size={30} color="white" />
        </View>
      </TouchableOpacity>
    );
  };

  checkCouponCode = () => {
    if (this.getExistCoupon() == 0) {
      this.props.getCouponAmount(this.state.coupon);
    } else {
      this.props.cleanOldCoupon();
    }
  };

  getCouponString = () => {
    const { discountType } = this.props;
    const couponValue = this.getExistCoupon();
    if (discountType == "percent") {
      return `${couponValue * 100}%`;
    }
    return Tools.getCurrecyFormatted(couponValue);
  };

  getExistCoupon = () => {
    const { couponCode, couponAmount, discountType } = this.props;
    if (couponCode == this.state.coupon) {
      if (discountType == "percent") {
        return couponAmount / 100.0;
      }
      return couponAmount;
    }
    return 0;
  };
}

MyCart.defaultProps = {
  couponCode: "",
  couponAmount: 0,
};

const mapStateToProps = ({ carts, products }) => {
  return {
    cartItems: carts.cartItems,
    totalPrice: carts.totalPrice,
    couponCode: products.coupon && products.coupon.code,
    couponAmount: products.coupon && products.coupon.amount,
    discountType: products.coupon && products.coupon.type,

    isFetching: products.isFetching,
    type: products.type,
    message: products.message,
  };
};

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { dispatch } = dispatchProps;
  const { actions } = require("@redux/CartRedux");
  const productActions = require("@redux/ProductRedux").actions;
  return {
    ...ownProps,
    ...stateProps,
    removeCartItem: (product, variation) => {
      actions.removeCartItem(dispatch, product, variation);
    },
    cleanOldCoupon: () => {
      productActions.cleanOldCoupon(dispatch);
    },
    getCouponAmount: (coupon) => {
      productActions.getCouponAmount(dispatch, coupon);
    },
  };
}

export default connect(
  mapStateToProps,
  undefined,
  mergeProps
)(withTheme(MyCart));
