/** @format */

import React, { PureComponent } from "react";
import { Text, View } from "react-native";
import { isArray } from "lodash";

import { Languages, withTheme, Tools } from "@common";
import styles from "./styles";

class ConfirmCheckout extends PureComponent {
  render() {
    const {
      discountType,
      couponAmount,
      shippingMethod,
      totalPrice,
      totalStyle,
      labelStyle,
      style,
    } = this.props;
    const shippingPrice = isArray(shippingMethod)
      ? shippingMethod[0].total
      : shippingMethod;
    const discount =
      discountType == "percent"
        ? this.getExistCoupon() * totalPrice
        : this.getExistCoupon();
    const total =
      parseFloat(totalPrice) +
      parseFloat(shippingPrice) -
      parseFloat(discount || 0);
    const {
      theme: {
        colors: { text },
      },
    } = this.props;

    return (
      <View style={[styles.container, style]}>
        <View style={styles.row}>
          <Text style={[styles.label, labelStyle]}>{Languages.Subtotal}</Text>
          <Text style={[styles.value, { color: text }]}>
            {Tools.getCurrecyFormatted(totalPrice)}
          </Text>
        </View>
        {couponAmount > 0 && (
          <View style={styles.row}>
            <Text style={[styles.label, labelStyle]}>{Languages.Discount}</Text>
            <Text style={[styles.value, { color: text }]}>
              {discountType == "percent"
                ? `${parseFloat(couponAmount)}%`
                : Tools.getCurrecyFormatted(couponAmount)}
            </Text>
          </View>
        )}
        <View style={styles.row}>
          <Text style={[styles.label, labelStyle]}>{Languages.Shipping}</Text>
          <Text style={[styles.value, { color: text }]}>
            {Tools.getCurrecyFormatted(shippingPrice)}
          </Text>
        </View>
        <View style={styles.divider} />
        <View style={styles.row}>
          <Text style={[styles.label, labelStyle]}>{Languages.Total}</Text>
          <Text style={[styles.value, { color: text }, totalStyle]}>
            {Tools.getCurrecyFormatted(total)}
          </Text>
        </View>
      </View>
    );
  }

  getExistCoupon = () => {
    const { couponAmount, discountType } = this.props;
    if (discountType == "percent") {
      return couponAmount / 100.0;
    }
    return couponAmount;
  };
}

export default withTheme(ConfirmCheckout);
