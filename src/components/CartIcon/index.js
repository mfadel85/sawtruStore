/**
 * Created by InspireUI on 07/03/2017.
 *
 * @format
 */

import React from "react";
import PropTypes from "prop-types";

import { Icons } from "@common";
import { ToolbarIcon } from "@components";

class CartIcon extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { number, onPress, color } = this.props;
    return (
      <ToolbarIcon
        name={
          number
            ? Icons.MaterialCommunityIcons.ShoppingCart
            : Icons.MaterialCommunityIcons.ShoppingCartEmpty
        }
        number={number}
        color={color}
        onPress={onPress}
      />
    );
  }
}

CartIcon.propTypes = {
  number: PropTypes.number.isRequired,
};

CartIcon.defaultProps = {
  number: 0,
};

export default CartIcon;
