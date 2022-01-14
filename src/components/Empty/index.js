/**
 * Created by InspireUI on 22/12/2016.
 *
 * @format
 */

import React from "react";
import PropTypes from "prop-types";
import { View, Text, StyleSheet } from "react-native";

import { Styles, Color } from "@common";

class Empty extends React.Component {
  render() {
    const { text } = this.props;
    return (
      <View style={styles.container}>
        <Text style={styles.text}>{text}</Text>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    ...Styles.Common.ColumnCenter,
    flexGrow: 1,
  },
  text: {
    color: Color.lightText,
  },
});

Empty.propTypes = {
  text: PropTypes.string,
};
Empty.defaultProps = {
  text: "Empty",
};

export default Empty;
