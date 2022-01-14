/** @format */

import React from "react";
import {
  View,
  Image,
  TextInput,
  TouchableOpacity,
  Animated,
  Text,
} from "react-native";
import styles from "./style";
import ExpandComponent from "../ExpandComponent";
import Item from "../ChipItem";
import Icon from "react-native-vector-icons/Ionicons";
import { Languages, Icons, withTheme } from "@common";

class ProductTags extends React.Component {
  state = {
    selectedId: -1,
    expanded: true,
  };

  static defaultProps = {
    tags: [],
  };

  render() {
    const { tags } = this.props;
    const { selectedId, expanded } = this.state;
    const {
      theme: {
        colors: { background, text },
      },
    } = this.props;

    return (
      <ExpandComponent
        ref="expandComponent"
        contentView={
          <TouchableOpacity onPress={this.toggle} style={styles.button}>
            <Text style={[styles.text, { color: text }]}>
              {Languages.ProductTags}
            </Text>
            <Icon
              name={
                expanded ? Icons.Ionicons.DownArrow : Icons.Ionicons.RightArrow
              }
              size={20}
              color={text}
            />
          </TouchableOpacity>
        }
        expandView={
          <View style={styles.container}>
            {tags.map((item, index) => (
              <Item
                item={item}
                key={index}
                label={item.name}
                onPress={this.onPress}
                selected={selectedId == item.id}
              />
            ))}
          </View>
        }
        onChangeStatus={this.onChangeStatus}
      />
    );
  }

  onPress = (item) => {
    this.setState({ selectedId: item.id });
    this.props.onSelectTag(item);
  };

  onChangeStatus = (expanded) => {
    this.setState({ expanded });
  };

  toggle = () => {
    this.refs.expandComponent.toggle();
  };
}

export default withTheme(ProductTags);
