/** @format */

import React from "react";
import { View } from "react-native";
import { connect } from "react-redux";

import { log } from "@app/Omni";
import { actions as BrandsActions } from "@redux/BrandsRedux";
import { actions as TagActions } from "@redux/TagRedux";
import Item from "./Item";

class Content extends React.PureComponent {
  state = { selected: this.props.selected };

  componentDidMount() {
    const { type, fetchBrands, fetchTags, list } = this.props;

    if (!list || (list && !list.length)) {
      switch (type) {
        case "brand":
          fetchBrands();
          break;

        case "tag":
          fetchTags();
          break;

        default:
          break;
      }
    }
  }

  _onSelect = (item, type) => {
    this.setState({ selected: item }, () => {
      this.props.onSelect(item, type);
    });
  };

  render() {
    const { selected } = this.state;
    const { list, type } = this.props;

    return (
      <View>
        {list.map((o, i) => {
          return (
            <Item
              isSelect={selected && selected.name === o.name}
              key={i.toString()}
              item={o}
              onPress={() => this._onSelect(o, type)}
            />
          );
        })}
      </View>
    );
  }
}

const mapStateToProps = (state, ownProps) => {
  let list = [];

  if (ownProps.storeName === "category") {
    const { categories } = state;
    const subId =
      categories.selectedCategory && categories.selectedCategory.mainCategory
        ? categories.selectedCategory.mainCategory.id
        : categories.selectedCategory.parent;
    list = [
      categories.selectedCategory.mainCategory,
      ...categories.list.filter((category) => category.parent === subId),
    ];
  } else {
    list =
      ownProps && ownProps.storeName && state[ownProps.storeName]
        ? state[ownProps.storeName].list
        : [];
  }

  return {
    list,
  };
};

const mapDispatchToProps = (dispatch) => {
  return {
    fetchBrands: () => {
      dispatch(BrandsActions.fetchBrands());
    },
    fetchTags: () => {
      TagActions.fetchTags(dispatch);
    },
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Content);
