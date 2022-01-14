/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { View, Text, FlatList } from "react-native";
import { Constants, Languages, withTheme } from "@common";
import { PostLayout } from "@components";
import { connect } from "react-redux";
// import { warn } from '@app/Omni'
import styles from "./styles";

class ProductRelated extends PureComponent {
  static propTypes = {
    tags: PropTypes.any,
    fetchProductRelated: PropTypes.func,
    onViewProductScreen: PropTypes.func,
    productRelated: PropTypes.array,
  };

  componentWillMount() {
    const { product } = this.props;
    this.props.fetchProductRelated(product);
  }

  onRowClickHandle = (product) => this.props.onViewProductScreen({ product });

  render() {
    const { productRelated } = this.props;
    const {
      theme:{
        colors:{
          background, text
        }
      }
    } = this.props

    if (productRelated.length == 0) {
      return <View />
    }
    return (
      <View style={[styles.wrap, {backgroundColor: background}]}>
        <View style={styles.head}>
          <Text style={[styles.headTitle, {color: text}]}>{Languages.ProductRelated}</Text>
        </View>
        <View style={styles.flatlist}>
          <FlatList
          horizontal={true}
          data={productRelated}
          renderItem={({item, index})=>(
            <PostLayout
              post={item}
              key={`key-${index}`}
              onViewPost={() => this.onRowClickHandle(item)}
              layout={Constants.Layout.threeColumn}
            />
          )}
          />
        </View>
      </View>
    );
  }
}

ProductRelated.defaultProps = {
  productRelated: []
}

const mapStateToProps = ({ products }) => ({
  productRelated: products.productRelated,
});

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { dispatch } = dispatchProps;
  const ProductRedux = require("@redux/ProductRedux");

  return {
    ...ownProps,
    ...stateProps,
    fetchProductRelated: (product) => {
      ProductRedux.actions.fetchProductRelated(dispatch, product);
    },
  };
}

export default connect(
  mapStateToProps,
  undefined,
  mergeProps
)(withTheme(ProductRelated));
