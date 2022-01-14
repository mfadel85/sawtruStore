/** @format */

import React, { PureComponent } from "react";
import {
  Animated,
  ScrollView,
  View,
  FlatList,
  TouchableOpacity,
} from "react-native";
import { connect } from "react-redux";
import { Button, AddressItem, AnimatedHeader } from "@components";
import { Constants, Languages, withTheme } from "@common";
import styles from "./styles";
import _ from 'lodash'

class Address extends PureComponent {

  render() {
    let {list, reload, selectedAddress} = this.props
    const {
      theme:{
        colors:{
          background, text
        }
      }
    } = this.props

    return (
      <FlatList
        style={{backgroundColor: background}}
        extraData={this.props}
        keyExtractor={(item, index)=>`${index}`}
        data={list}
        renderItem={({item, index})=><AddressItem onPress={()=>this.selectAddress(item)} selected={_.isEqual(item, selectedAddress)} item={item} onRemove={()=>this.removeAddress(index)}/>}/>
    )
  }

  removeAddress = (index)=>{
    let item = this.props.list[index]
    this.props.removeAddress(index)
  }

  selectAddress = (item)=>{
    this.props.selectAddress(item)
  }
}

Address.defaultProps = {
  list: [],
  selectedAddress: {}
}

const mapStateToProps = (state) => {
  return {
    list: state.addresses.list,
    reload: state.addresses.reload,
    selectedAddress: state.addresses.selectedAddress
  };
};

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { dispatch } = dispatchProps;
  const {actions} = require("@redux/AddressRedux");
  return {
    ...ownProps,
    ...stateProps,
    removeAddress: (index) => {
      actions.removeAddress(dispatch, index);
    },
    selectAddress: (address) => {
      actions.selectAddress(dispatch, address);
    },
  };
}

export default connect(
  mapStateToProps,
  undefined,
  mergeProps
)(withTheme(Address));
