/** @format */

import React, { Component } from "react";
import {
  StyleSheet,
  Text,
  View,
  Image,
  TouchableOpacity,
  I18nManager,
} from "react-native";
import styles from "./ReviewItem_Style.js";
import Rating from "@components/Rating";
import moment from "moment";
import { Constants } from "@common";

export default class ReviewItem extends Component {
  render() {
    let { review } = this.props;
    return (
      <View style={styles.container}>
        <View style={[I18nManager.isRTL && { alignItems: "flex-end" }]}>
          <Text style={styles.name}>{review.name}</Text>
        </View>
        <View style={[I18nManager.isRTL && { alignItems: "flex-end" }]}>
          <Text style={styles.review}>{review.review}</Text>
        </View>
        <View
          style={{
            flexDirection: I18nManager.isRTL ? "row-reverse" : "row",
            justifyContent: "space-between",
          }}>
          <Text style={styles.date_created}>
            {this.dateFormat(review.date_created)}
          </Text>
          <Rating rating={review.rating} style={styles.rating} />
        </View>
        <View style={styles.separator} />
      </View>
    );
  }

  dateFormat(date) {
    return moment.parseZone(date).format("MMMM DD, YYYY, HH:mm");
  }
}
