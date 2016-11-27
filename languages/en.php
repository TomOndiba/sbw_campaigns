<?php

return [

	'campaigns' => 'Campaigns',
	'ojbect:campaign' => 'Campaign',
	'item:object:campaign' => 'Campaigns',

	'campaigns:all' => 'All Campaigns',
	'campaigns:mine' => 'My Campaigns',
	'campaigns:owner' => '%s\'s Campaigns',
	'campaigns:friends' => 'Friends\'s Campaigns',
	'campaigns:group' => 'Group Campaigns',
	'campaigns:add' => 'Start a Campaign',
	'campaigns:edit' => 'Edit Campaign',

	'campaigns:field:title' => 'Title',
	'campaigns:field:description' => 'Description',
	'campaigns:field:tags' => 'Tags',
	'campaigns:field:access' => 'Access',
	'campaigns:field:icon' => 'Icon',
	'campaigns:field:model' => 'Model',
	'campaigns:field:calendar_start' => 'Start date',
	'campaigns:field:calendar_end' => 'End date',
	'campaigns:field:target_amount' => 'Target amount',
	'campaigns:field:donation_minimum' => 'Minimum donation amount',
	'campaigns:field:funding_period' => 'Funding period',
	'campaigns:field:currency' => 'Currency',
	'campaigns:field:target_unit' => 'Unit type',
	'campaigns:field:terms' => 'I agree to %s',
	'campaigns:field:video_url' => 'Cover video URL',
	'campaigns:field:website' => 'Website',
	'campaigns:field:managers' => 'Managers',
	'campaigns:field:managers:help' => 'Select users that will have managerial permissions for this campaign',
	'campaigns:field:status' => 'Status',
	'campaigns:field:rules' => 'Rules',
	'campaigns:field:rules:help' => 'Specify terms and conditions that apply to individual donations',
	'campaigns:field:quantity' => 'Available quantity',
	'campaigns:field:location' => 'Location',
	'campaigns:field:briefdescription' => 'Brief description',

	'campaigns:status:published' => 'Published',
	'campaigns:status:draft' => 'Draft',
	'campaigns:status:pending_verification' => 'Pending verification',
	'campaigns:status:starting_soon' => 'Starting soon',
	'campaigns:status:ongoing' => 'Ongoing',
	'campaigns:status:ended' => 'Ended',

	'campaigns:model:tipping_point' => 'Tipping Point',
	'campaigns:model:tipping_point:help' => '
		This campaign has a set target, which must be reached before campaign\'s end.
		If the target is not reached, all donations will be refunded.
		Suitable for campaigns that will only be able to achieve their goals at 100% funding.
	',
	'campaigns:model:pot' => 'Money Pot',
	'campaigns:model:pot:help' => '
		All donations made to the campaign will be received by the organizer when the campaign ends.
		Suitable for campaigns that use the funding towards their day-to-day operations and do not require 100% funding to achieve their goals.
	',
	'campaigns:model:relief' => 'Relief',

	'campaigns:no_results' => 'There are no campaigns yet',

	'campaigns:edit:about' => 'About',
	'campaigns:edit:rewards' => 'Rewards',
	'campaigns:edit:transactions' => 'Transactions',
	'campaigns:edit:balance' => 'Pay-Out',

	'campaigns:news:no_results' => 'This campaign hasn\'t published any news yet',
	'campaigns:rewards:no_results' => 'This campaign does not offer any rewards',
	'campaigns:transactions:no_results' => 'There are no transactions to display yet',

	'campaigns:terms:campaigner' => 'Campaigner Terms and Conditions',
	'campaigns:terms:donor' => 'Donor Terms and Conditions',
	'campaigns:terms:campaign' => '%s Rules',

	'campaigns:success' => '%s has been successfully saved',
	'campaigns:error:not_found' => 'Item not found',
	'campaigns:error:container_permissions' => 'You do not have sufficient permissions for this action',
	'campaigns:error:permissions' => 'You do not have sufficient permissions for this action',
	'campaigns:error:required' => 'One or more required fields are missing',
	'campaigns:error:dates' => 'The end date can not precede the start date',
	'campaigns:error:terms' => 'You must agree to terms and conditions before proceeding',
	'campaigns:error:general' => 'An unknown error during this action',
	'campaigns:error:reward_minimum_too_low' => 'Minimum donation amount can not be lower than the donation amount of %s %s set for the campaign',
	'campaigns:error:quantity_too_low' => 'Quantity should be 1 or more',
	'campaigns:error:publish_without_rewards' => 'Campaigns must have at least one reward to be eligible for publishing',
	'campaigns:error:already_published' => 'Campaign has already been published',
	'campaigns:error:already_verified' => 'Campaign has already been verified',
	'campaigns:error:donation_amount_too_low' => 'The donation amount is lower than the required minimum of %s for the selected reward',
	'campaigns:error:payment_method_required' => 'Please select a payment method',
	'campaigns:error:checkout_terms' => 'You must agree to Donor Terms and Conditions and Campaign Rules',
	'campaigns:error:payment_gateway' => 'There was an error contacting the payment gateway. Please try again later.',
	'campaigns:error:already_started' => 'Campaign has already started',
	'campaigns:error:already_ended' => 'Campaign has already ended',
	
	'campaigns:require_verification' => 'Require verification',
	'campaigns:require_verification:help' => 'If enabled, campaigns will have to be verified by the administrator before starting',
	'campaigns:cutoff_time' => 'Cut-off time',
	'campaigns:cutoff_time:help' => 'All campaigns will be started and stopped at a given cut-off time of a day via cron',
	'campaigns:payments:enable_paypal' => 'Enable PayPal payments',
	'campaigns:payments:enable_paypal:help' => 'Allow donors to pledge and donate funds via PayPal',
	'campaigns:payments:paypal_percentile_fee' => 'PayPal processing fee',
	'campaigns:payments:paypal_percentile_fee:help' => 'Enter percentile amount of the PayPal processing as percentage of the total amount of the donation (e.g. 3.9)',
	'campaigns:payments:paypal_flat_fee' => 'PayPal processing fee',
	'campaigns:payments:paypal_flat_fee:help' => 'Enter flat amount of the PayPal processing fee in the currency of the donation (e.g. 0.35)',
	'campaigns:payments:wire_percentile_fee' => 'Wire transfer processing fee',
	'campaigns:payments:wire_percentile_fee:help' => 'Enter percentile amount of the wire transfer processing as percentage of the total amount of the donation (e.g. 3.9)',
	'campaigns:payments:wire_flat_fee' => 'Wire transfer processing fee',
	'campaigns:payments:wire_flat_fee:help' => 'Enter flat amount of the wire transfer processing fee in the currency of the donation (e.g. 0.35)',
	'campaigns:payments:stripe_percentile_fee' => 'Stripe processing fee',
	'campaigns:payments:stripe_percentile_fee:help' => 'Enter percentile amount of the Stripe processing as percentage of the total amount of the donation (e.g. 3.9)',
	'campaigns:payments:stripe_flat_fee' => 'Stripe processing fee',
	'campaigns:payments:stripe_flat_fee:help' => 'Enter flat amount of the Stripe processing fee in the currency of the donation (e.g. 0.35)',
	'campaigns:payments:enable_sofort' => 'Enable Sofort payments',
	'campaigns:payments:enable_sofort:help' => 'Allow donors to pledge and donate funds via Sofort',
	'campaigns:payments:sofort_percentile_fee' => 'Sofort processing fee',
	'campaigns:payments:sofort_percentile_fee:help' => 'Enter percentile amount of the Sofort processing as percentage of the total amount of the donation (e.g. 3.9)',
	'campaigns:payments:sofort_flat_fee' => 'Sofort processing fee',
	'campaigns:payments:sofort_flat_fee:help' => 'Enter flat amount of the Sofort processing fee in the currency of the donation (e.g. 0.35)',

	'campaigns:payments:tipping_point_fee' => 'Tipping Point Campaign Fee',
	'campaigns:payments:tipping_point_fee:help' => 'Enter amount (in percentage) of the donation amount to be withheld by the site',
	'campaigns:payments:money_pot_fee' => 'Money Pot Campaign Fee',
	'campaigns:payments:money_pot_fee:help' => 'Enter amount (in percentage) of the donation amount to be withheld by the site',

	'campaigns:funding_period' => '%s - %s',
	'campaigns:ends:in_days' => '%s days left',
	'campaigns:ends:in_hours' => '%s hours left',
	'campaigns:ends:in_minutes' => '%s minutes left',
	'campaigns:ends:now' => 'Ending now',

	
	'campaigns:remove_manager:notify:subject' => 'You have been removed from the campaign team',
	'campaigns:remove_manager:notify:body' => '
		Dear %s,

		%s has removed your from the campaign team for %s.
	',

	'campaigns:add_manager:notify:subject' => 'You have been added to the campaign team',
	'campaigns:add_manager:notify:body' => '
		Dear %s,

		%s has added you to the campaign team for %s.

		You can manage the campaign here:
		%s
	',

	'campaigns:publish:notify:subject' => 'Campaign is pending verification',
	'campaigns:publish:notify:body' => '
		%s has published a campaign "%s" and it is pending verification.
		
		To verify the campaign, please visit:
		%s
	',

	'campaigns:start:notify:subject' => '%s has started',
	'campaigns:start:notify:summary' => 'Contribute to %s until %s',
	'campaigns:start:notify:body' => '
		%s campaign by %s has started and you can make your contribution until %s.

		%s
		
		To make a contribution, please visit:
		%s
	',

	'campaigns:milestone:notify:subject' => '%s is %s funded',
	'campaigns:milestone:notify:summary' => '%s is %s funded',
	'campaigns:milestone:notify:body' => '
		%s campaign by %s has reached %s of its target. You can make a contribution before %s.

		%s

		To make a contribution, please visit:
		%s
	',

	'campaigns:end:notify:subject' => '%s has ended reaching %s funding',
	'campaigns:end:notify:summary' => '%s has ended reaching %s funding',
	'campaigns:end:notify:body' => '
		%s campaign by %s has ended reaching %s of its target.

		%s

		To see campaign details, please visit:
		%s
	',

	'campaigns:transaction:paid:notify:subject' => 'Your donation to %s has been received',
	'campaigns:transaction:paid:notify:body' => '
		Your donation of %s via %s to %s has been received.

		You can track campaign progress here:
		%s
	',

	'campaigns:transaction:failed:notify:subject' => 'Your donation to %s has failed',
	'campaigns:transaction:failed:notify:body' => '
		We were not able to process your donation of %s via %s to %s.

		If you believe there was a mistake, please send us an email with the ID of this transaction:
		%s

		You can retry the donation with a different payment method here:
		%s
	',

	'campaigns:transaction:refunded:notify:subject' => 'Your donation to %s has failed',
	'campaigns:transaction:refunded:notify:body' => '
		Your donation of %s via %s to %s has been refunded.

		You can see the results of the campaign here:
		%s
	',

	'campaigns:news:notify:subject' => 'Campaign news: %s',
	'campaigns:news:notify:body' => '
		%s

		%s

		%s

		Read full article here:
		%s

	',

	'campaigns:balance:paid:notify:subject' => 'Campaign balance for %s has been paid out',
	'campaigns:balance:paid:notify:body' => '
		Campaign balance for %s has been paid out via %s. The pay-out amount is %s.

		You can view the campaign details here:
		%s
	',

	'campaigns:refund:failed:notify:subject' => 'Automatic refund has failed',
	'campaigns:refund:failed:notify:body' => '
		Automatic refund for a tipping point campaign contribution to %s has failed.

		The refund in the amount of %s to %s could not be completed.

		Please refund this transaction manually here:
		%s

		To view the campaign, please visit:
		%s
	',

	'campaigns:refund:pending:notify:subject' => 'Manual refund is required',
	'campaigns:refund:pending:notify:body' => '
		Refund for a tipping point campaign contribution to %s must be made manually.

		The refund in the amount of %s to %s is pending.

		Please manually refund the payment and log the transaction here:
		%s

		To view the campaign, please visit:
		%s
	',

	'campaigns:edit:news' => 'News',
	'campaigns:news' => 'Campaign news',
	'campaigns:news:add' => 'Add news',
	'campaigns:news:edit' => 'Edit news',

	'campaigns:rewards' => 'Rewards',
	'campaigns:rewards:add' => 'Add reward',
	'campaigns:rewards:edit' => 'Edit reward',

	'campaigns:rewards:in_stock' => '%s remaining',
	'campaigns:rewards:out_of_stock' => 'Out of stock',
	'campaigns:rewards:donation_minimum' => 'Minimum donation: %s %s',

	'campaigns:acl:managers' => 'Campaign Team: %s',

	'campaigns:about' => 'About the campaign',
	'campaigns:rules' => 'Campaign rules',

	'campaigns:verify' => 'Verify',
	'campaigns:verify:success' => '%s has been verified',

	'campaigns:publish' => 'Publish',
	'campaigns:publish:success' => '%s has been published',

	'campaigns:pledge' => 'Pledge',
	'campaigns:donate' => 'Donate',

	'campaigns:give:pledge' => 'Pledge to %s',
	'campaigns:give:donate' => 'Donate to %s',
	'campaigns:give:no_reward' => 'Give to this campaign without a reward',
	'campaigns:give:get_reward' => 'Get reward',
	'campaigns:give:payment_method' => 'Payment Method',
	'campaigns:give:reward' => 'Reward',
	'campaigns:give:amount' => 'Enter amount',

	'campaigns:payment_method:no_fee' => ' (no processing fee)',
	'campaigns:payment_method:fee' => ' (%s)',

	'campaigns:contribution' => 'Campaign contribution',
	'campaigns:contribution:from' => 'Campaign contribution from %s',
	'campaigns:contribution:target' => 'Campaign contribution to %s',

	'campaigns:checkout' => 'Checkout',
	'campaigns:checkout:charges:paypal_fee' => 'PayPal Fee',

	'campaigns:checkout:donor' => 'Donor Details',
	'campaigns:checkout:email' => 'Email',
	'campaigns:checkout:name' => 'Name',
	'campaigns:checkout:first_name' => 'First Name',
	'campaigns:checkout:last_name' => 'Last Name',
	'campaigns:checkout:company_name' => 'Company Name',
	'campaigns:checkout:tax_id' => 'Tax ID',
	'campaigns:postal_address:street_address' => 'Street Address',
	'campaigns:postal_address:extended_address' => 'Street Address 2',
	'campaigns:postal_address:locality' => 'City/Town',
	'campaigns:postal_address:region' => 'Region/State',
	'campaigns:postal_address:postal_code' => 'Postal Code',
	'campaigns:postal_address:country' => 'Country',

	'campaigns:checkout:anonymize' => 'Keep this donation anonymous in campaign listings',
	'campaigns:checkout:subscribe' => 'Subscribe me to campaign updates',

	'campaigns:cancel' => 'Cancel',
	'campaigns:checkout:pay' => 'Pay Now',

	'campaigns:payment:paypal:help' => '
		After submitting this form, you will be redirected to the PayPal website to complete your payment.
	',

	'campaigns:anonymous' => 'Anonymous',
	'campaigns:donations' => 'Donations',
	'campaigns:donations:no_results' => 'There are no donations to this campaign yet',
	'campaigns:funded_percentage' => '%s funded',
	'campaigns:funded' => '%s funded',
	'campaigns:backers' => '%s backers',
	'campaigns:avg_donation' => '%s avg donation',

	'campaigns:manual_start' => 'Start campaign',
	'campaigns:manual_start:confirm' => 'Are you sure you want to start this campaign outside of its normal schedule?',
	'campaigns:manual_end' => 'End campaign',
	'campaigns:manual_end:confirm' => 'Are you sure you want to end this campaign outside of its normal schedule? Campaigns that have not reached their targets will be terminated and, if applicable, refunds will be made',
	'campaigns:start:success' => '%s has been started',
	'campaigns:end:success' => '%s has been ended',

	'river:end:object:default' => 'Campaign %2$s has ended',
	'river:give:object:default' => '%s donated to %s campaign',
	'river:publish:object:default' => '%s create a new campaign %s',
	'river:start:object:default' => 'Campaign %2$s has started',
	'river:milestone:object:default' => 'Campaign %2$s has reached a new milestone',

	'campaigns:transactions:no_results' => 'There are not transactions yet',

	'river:create:object:campaign_news' => '%s posted %s in %s',

	'admin:payments:campaign_balances' => 'Campaign Pay-Outs',
	'campaigns:balances:no_results' => 'There are no campaign pay-outs yet',
	'campaigns:edit:balance:no_results' => 'Final pay-out amount will be displayed here once the campaign has ended.',
	'campaigns:edit:balance:target_not_reached' => 'Campaign has not reached it\'s target or has not received any donations',
	'campaigns:edit:payout:success' => 'Payout information has been updated',
	'campaigns:balance' => 'Balance',
	'campaigns:payout:details' => 'Balance payout instructions',
	'campaigns:payout:account' => 'Bank account',
	'campaigns:payout:account:help' => 'Enter your IBAN account number, BIC and recipient information',
	'campaigns:payout:no_information' => 'Payout information has not been provided yet',
	'campaigns:payout:recipient' => 'Recipient Details',

	'payments:charges:processor_fee' => 'Processor Fees',

	'campaigns:edit' => 'Manage',
	'campaigns:transactions:view' => 'View Transactions',
	
	'campaigns:follow' => 'Follow',
	'campaigns:follow:success' => 'You are now subscribed to notifications about %s',
	
	'campaigns:unfollow' => 'Unfollow',
	'campaigns:unfollow:success' => 'You are now longer subscribed to notifications about %s',

	'campaigns:checkout:register' => 'Create a new user account',
	
];