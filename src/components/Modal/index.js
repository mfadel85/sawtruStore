/** @format */

import Icon from "@expo/vector-icons/SimpleLineIcons";
import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { Platform, View, TouchableOpacity } from "react-native";
import Modal from "react-native-modalbox";
import styles from "./styles";
import { withTheme } from "@common";

@withTheme
export default class ModalBox extends PureComponent {
  static propTypes = {
    children: PropTypes.node,
    css: PropTypes.any,
    type: PropTypes.string,
  };

  closeModal = () => {
    this.modal.close();
  };

  closeModalLayout() {
    this.modal.close();
  }

  openModal() {
    this.modal.open();
  }

  render() {
    const { type, css } = this.props;
    const {
      theme: {
        colors: { background, text },
      },
    } = this.props;

    return (
      <Modal
        ref={(modal) => (this.modal = modal)}
        animationDuration={100}
        backdropOpacity={Platform.OS === "android" ? 0.9 : 0.5}
        position="top"
        style={[
          typeof type !== "undefined"
            ? styles.modalReadlater
            : styles.modalBoxWrap,
          css,
        ]}>
        <View style={[styles.wrap, { backgroundColor: background }]}>
          {this.props.children}
        </View>

        <TouchableOpacity style={styles.iconZoom} onPress={this.closeModal}>
          <Icon
            style={styles.textClose}
            name="close"
            size={22}
            color={text}
            backgroundColor="transparent"
          />
        </TouchableOpacity>
      </Modal>
    );
  }
}
