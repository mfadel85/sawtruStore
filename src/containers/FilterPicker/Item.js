/**
 * Created by InspireUI on 03/03/2017.
 *
 * @format
 */

import React from "react";
import PropTypes from "prop-types";
import { TouchableOpacity, Text, StyleSheet, View } from "react-native";
import { Styles, Color, Icons, withTheme } from "@common";
import { Icon } from "@app/Omni";

class Item extends React.PureComponent {
  render() {
    const { onPress, item, isSelect, isFirst } = this.props;
    const {
      theme: {
        colors: { text },
      },
    } = this.props;

    if (!item) return null;

    return (
      <View style={[styles.container, isFirst ? { borderTopWidth: 0 } : {}]}>
        <TouchableOpacity style={styles.subContainer} onPress={onPress}>
          <View style={[styles.checkboxWrap, { borderColor: text }]}>
            {isSelect ? (
              <Icon
                name={Icons.MaterialCommunityIcons.CheckMark}
                size={20}
                color={text}
              />
            ) : null}
          </View>
          <Text style={[styles.text, { color: text }]}>
            {(isFirst ? "" : "--- ") + item.name}
          </Text>
        </TouchableOpacity>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {},
  subContainer: {
    ...Styles.Common.RowCenterLeft,
    padding: 10,
  },
  checkboxWrap: {
    height: 20,
    width: 20,
    borderColor: Color.blackTextSecondary,
    borderWidth: 1,
    borderRadius: 5,
    ...Styles.Common.ColumnCenter,
  },
  text: {
    marginLeft: 10,
    color: Color.blackTextPrimary,
  },
});

Item.propTypes = {
  item: PropTypes.object.isRequired,
  onPress: PropTypes.func,
  isSelect: PropTypes.bool,
  isFirst: PropTypes.bool,
};

export default withTheme(Item);
