<?php

namespace App\Services;

use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\SettingPolicy;

class CommonService
{
    /**
     * validate email
     * @param  string $email
     * @return boolean
     */
    public function validateEmail($email)
    {
        if (!$email) {
            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * format date
     * @param  string $format
     * @param  string $date
     * @return string
     */
    public function formatDate($format, $date)
    {
        if (!$date) {
            return null;
        }
        return Carbon::parse($date)->format($format);
    }

    /**
     * get status flag
     * @param  integer $value
     * @return string
     */
    public function getStatusFlag($value)
    {
        return $value ? Lang::get('view.on') : Lang::get('view.off');
    }

    /**
     * call api with header and body
     * @param  array $header
     * @param  string $body
     * @param  string $url
     * @param  string $type
     * @param  boolean $isFormParams
     * @return array
     */
    public function callApi($header, $body, $url, $type, $isFormParams = false)
    {
        $bodyRequest = [
            $isFormParams ? 'form_params' : 'body'    => $body,
        ];
        if (!$isFormParams) {
            $bodyRequest['headers'] = $header;
        }
        $client = new \GuzzleHttp\Client();
        $result = $client->$type($url, $bodyRequest);
        $result = $result ->getBody()->getContents();
        $result = '<?xml version="1.0" encoding="utf-8"?>
<GetMyeBaySellingResponse xmlns="urn:ebay:apis:eBLBaseComponents">
    <!-- Call-specific Output Fields -->
    <ActiveList>
        <ItemArray>
            <Item>
                <BestOfferDetails>
                    <BestOfferCount>10</BestOfferCount>
                </BestOfferDetails>
                <BuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </BuyItNowPrice>
                <ClassifiedAdPayPerLeadFee currencyID="CurrencyCodeType"> AmountType (double) </ClassifiedAdPayPerLeadFee>
                <eBayNotes> string </eBayNotes>
                <HideFromSearch> boolean </HideFromSearch>
                <ItemID> ItemIDType (string) </ItemID>
                <LeadCount>10</LeadCount>
                <ListingDetails>
                    <ConvertedBuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedBuyItNowPrice>
                    <ConvertedReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedReservePrice>
                    <ConvertedStartPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedStartPrice>
                    <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                </ListingDetails>
                <ListingDuration> token </ListingDuration>
                <ListingType> ListingTypeCodeType </ListingType>
                <NewLeadCount>10</NewLeadCount>
                <PictureDetails> PictureDetailsType </PictureDetails>
                <PrivateNotes> string </PrivateNotes>
                <Quantity>10</Quantity>
                <QuantityAvailable>10</QuantityAvailable>
                <QuestionCount> long </QuestionCount>
                <ReasonHideFromSearch> ReasonHideFromSearchCodeType </ReasonHideFromSearch>
                <ReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ReservePrice>
                <SellerProfiles>
                    <SellerPaymentProfile>
                        <PaymentProfileID> long </PaymentProfileID>
                        <PaymentProfileName> string </PaymentProfileName>
                    </SellerPaymentProfile>
                    <SellerReturnProfile>
                        <ReturnProfileID> long </ReturnProfileID>
                        <ReturnProfileName> string </ReturnProfileName>
                    </SellerReturnProfile>
                    <SellerShippingProfile>
                        <ShippingProfileID> long </ShippingProfileID>
                        <ShippingProfileName> string </ShippingProfileName>
                    </SellerShippingProfile>
                </SellerProfiles>
                <SellingStatus>
                    <BidCount>10</BidCount>
                    <BidderCount> long </BidderCount>
                    <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                    <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                    <HighBidder>
                        <FeedbackRatingStar> FeedbackRatingStarCodeType </FeedbackRatingStar>
                        <FeedbackScore>10</FeedbackScore>
                        <UserID>1235766</UserID>
                    </HighBidder>
                    <PromotionalSaleDetails>
                        <EndTime>2004-08-04T19:09:02.768Z</EndTime>
                        <OriginalPrice currencyID="CurrencyCodeType"> AmountType (double) </OriginalPrice>
                        <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                    </PromotionalSaleDetails>
                    <QuantitySold>10</QuantitySold>
                    <ReserveMet> boolean </ReserveMet>
                </SellingStatus>
                <ShippingDetails>
                    <GlobalShipping> boolean </GlobalShipping>
                    <ShippingServiceOptions>
                        <LocalPickup> boolean </LocalPickup>
                        <ShippingServiceCost currencyID="CurrencyCodeType"> AmountType (double) </ShippingServiceCost>
                    </ShippingServiceOptions>
                    <ShippingType> ShippingTypeCodeType </ShippingType>
                </ShippingDetails>
                <SKU> SKUType (string) </SKU>
                <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                <TimeLeft> duration </TimeLeft>
                <Title> string </Title>
                <Variations>
                    <Variation>
                        <PrivateNotes> string </PrivateNotes>
                        <Quantity>10</Quantity>
                        <SellingStatus>
                            <BidCount>10</BidCount>
                            <BidderCount> long </BidderCount>
                            <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                            <CurrentPrice currencyID="CurrencyCodeType">1234</CurrentPrice>
                            <HighBidder>
                                <FeedbackRatingStar> FeedbackRatingStarCodeType </FeedbackRatingStar>
                                <FeedbackScore>10</FeedbackScore>
                                <UserID>1235766</UserID>
                            </HighBidder>
                            <PromotionalSaleDetails>
                                <EndTime>2004-08-04T19:09:02.768Z</EndTime>
                                <OriginalPrice currencyID="CurrencyCodeType"> AmountType (double) </OriginalPrice>
                                <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                            </PromotionalSaleDetails>
                            <QuantitySold>10</QuantitySold>
                            <ReserveMet> boolean </ReserveMet>
                        </SellingStatus>
                        <SKU> SKUType (string) </SKU>
                        <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                        <VariationSpecifics>
                            <NameValueList>
                                <Name> string </Name>
                                <Value> string </Value>
                                <!-- ... more Value values allowed here ... -->
                            </NameValueList>
                            <!-- ... more NameValueList nodes allowed here ... -->
                        </VariationSpecifics>
                        <!-- ... more VariationSpecifics nodes allowed here ... -->
                        <VariationTitle> string </VariationTitle>
                        <WatchCount> long </WatchCount>
                    </Variation>
                    <!-- ... more Variation nodes allowed here ... -->
                </Variations>
                <WatchCount> long </WatchCount>
            </Item>
            <!-- ... more Item nodes allowed here ... -->
        </ItemArray>
        <PaginationResult>
            <TotalNumberOfEntries>10</TotalNumberOfEntries>
            <TotalNumberOfPages>10</TotalNumberOfPages>
        </PaginationResult>
    </ActiveList>
    <DeletedFromSoldList>
        <OrderTransactionArray>
            <OrderTransaction>
                <Order>
                    <OrderID> OrderIDType (string) </OrderID>
                    <Subtotal currencyID="CurrencyCodeType"> AmountType (double) </Subtotal>
                    <TransactionArray>
                        <Transaction>
                            <Buyer>
                                <BuyerInfo>
                                    <ShippingAddress>
                                        <PostalCode> string </PostalCode>
                                    </ShippingAddress>
                                </BuyerInfo>
                                <Email> string </Email>
                                <StaticAlias> string </StaticAlias>
                                <UserID>1235766</UserID>
                            </Buyer>
                            <ConvertedTransactionPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedTransactionPrice>
                            <CreatedDate>2004-08-04T19:09:02.768Z</CreatedDate>
                            <FeedbackLeft>
                                FeedbackInfoType
                                <CommentType> CommentTypeCodeType </CommentType>
                            </FeedbackLeft>
                            <FeedbackReceived>
                                <CommentType> CommentTypeCodeType </CommentType>
                            </FeedbackReceived>
                            <IsMultiLegShipping> boolean </IsMultiLegShipping>
                            <Item>
                                <BuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </BuyItNowPrice>
                                <ClassifiedAdPayPerLeadFee currencyID="CurrencyCodeType"> AmountType (double) </ClassifiedAdPayPerLeadFee>
                                <HideFromSearch> boolean </HideFromSearch>
                                <ItemID> ItemIDType (string) </ItemID>
                                <ListingDetails>
                                    <ConvertedBuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedBuyItNowPrice>
                                    <ConvertedReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedReservePrice>
                                    <ConvertedStartPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedStartPrice>
                                    <EndTime>2004-08-04T19:09:02.768Z</EndTime>
                                    <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                                </ListingDetails>
                                <ListingType> ListingTypeCodeType </ListingType>
                                <PictureDetails> PictureDetailsType </PictureDetails>
                                <PrivateNotes> string </PrivateNotes>
                                <Quantity>10</Quantity>
                                <QuantityAvailable>10</QuantityAvailable>
                                <QuestionCount> long </QuestionCount>
                                <ReasonHideFromSearch> ReasonHideFromSearchCodeType </ReasonHideFromSearch>
                                <ReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ReservePrice>
                                <SellerProfiles>
                                    <SellerPaymentProfile>
                                        <PaymentProfileID> long </PaymentProfileID>
                                        <PaymentProfileName> string </PaymentProfileName>
                                    </SellerPaymentProfile>
                                    <SellerReturnProfile>
                                        <ReturnProfileID> long </ReturnProfileID>
                                        <ReturnProfileName> string </ReturnProfileName>
                                    </SellerReturnProfile>
                                    <SellerShippingProfile>
                                        <ShippingProfileID> long </ShippingProfileID>
                                        <ShippingProfileName> string </ShippingProfileName>
                                    </SellerShippingProfile>
                                </SellerProfiles>
                                <SellingStatus>
                                    <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                                    <CurrentPrice currencyID="CurrencyCodeType">11112</CurrentPrice>
                                    <QuantitySold>10</QuantitySold>
                                </SellingStatus>
                                <ShippingDetails>
                                    <GlobalShipping> boolean </GlobalShipping>
                                    <ShippingServiceOptions>
                                        <LocalPickup> boolean </LocalPickup>
                                        <ShippingServiceCost currencyID="CurrencyCodeType"> AmountType (double) </ShippingServiceCost>
                                    </ShippingServiceOptions>
                                    <!-- ... more ShippingServiceOptions nodes allowed here ... -->
                                    <ShippingType> ShippingTypeCodeType </ShippingType>
                                </ShippingDetails>
                                <SKU> SKUType (string) </SKU>
                                <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                                <TimeLeft> duration </TimeLeft>
                                <Title> string </Title>
                                <Variations>
                                    <Variation>
                                        <Quantity>10</Quantity>
                                        <SellingStatus>
                                            <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                                            <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                                            <QuantitySold>10</QuantitySold>
                                        </SellingStatus>
                                        <SKU> SKUType (string) </SKU>
                                        <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                                        <VariationSpecifics>
                                            <NameValueList>
                                                <Name> string </Name>
                                                <Value> string </Value>
                                                <!-- ... more Value values allowed here ... -->
                                            </NameValueList>
                                            <!-- ... more NameValueList nodes allowed here ... -->
                                        </VariationSpecifics>
                                        <!-- ... more VariationSpecifics nodes allowed here ... -->
                                        <VariationTitle> string </VariationTitle>
                                        <WatchCount> long </WatchCount>
                                    </Variation>
                                    <!-- ... more Variation nodes allowed here ... -->
                                </Variations>
                                <WatchCount> long </WatchCount>
                            </Item>
                            <OrderLineItemID> string </OrderLineItemID>
                            <PaidTime>2004-08-04T19:09:02.768Z</PaidTime>
                            <PaisaPayID> string </PaisaPayID>
                            <Platform> TransactionPlatformCodeType </Platform>
                            <QuantityPurchased>10</QuantityPurchased>
                            <SellerPaidStatus> PaidStatusCodeType </SellerPaidStatus>
                            <ShippedTime>2004-08-04T19:09:02.768Z</ShippedTime>
                            <Status>
                                <PaymentHoldStatus> PaymentHoldStatusCodeType </PaymentHoldStatus>
                            </Status>
                            <TotalPrice currencyID="CurrencyCodeType"> AmountType (double) </TotalPrice>
                            <TotalTransactionPrice currencyID="CurrencyCodeType"> AmountType (double) </TotalTransactionPrice>
                            <TransactionID> string </TransactionID>
                        </Transaction>
                        <!-- ... more Transaction nodes allowed here ... -->
                    </TransactionArray>
                </Order>
                <Transaction>
                    <Buyer>
                        <BuyerInfo>
                            <ShippingAddress>
                                <PostalCode> string </PostalCode>
                            </ShippingAddress>
                        </BuyerInfo>
                        <Email> string </Email>
                        <StaticAlias> string </StaticAlias>
                        <UserID>1235766</UserID>
                    </Buyer>
                    <ConvertedTransactionPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedTransactionPrice>
                    <CreatedDate>2004-08-04T19:09:02.768Z</CreatedDate>
                    <FeedbackLeft>
                        <CommentType> CommentTypeCodeType </CommentType>
                    </FeedbackLeft>
                    <FeedbackReceived>
                        <CommentType> CommentTypeCodeType </CommentType>
                    </FeedbackReceived>
                    <IsMultiLegShipping> boolean </IsMultiLegShipping>
                    <Item>
                        <BuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </BuyItNowPrice>
                        <ClassifiedAdPayPerLeadFee currencyID="CurrencyCodeType"> AmountType (double) </ClassifiedAdPayPerLeadFee>
                        <HideFromSearch> boolean </HideFromSearch>
                        <ItemID> ItemIDType (string) </ItemID>
                        <ListingDetails>
                            <ConvertedBuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedBuyItNowPrice>
                            <ConvertedReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedReservePrice>
                            <ConvertedStartPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedStartPrice>
                            <EndTime>2004-08-04T19:09:02.768Z</EndTime>
                            <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                        </ListingDetails>
                        <ListingType> ListingTypeCodeType </ListingType>
                        <PictureDetails> PictureDetailsType </PictureDetails>
                        <PrivateNotes> string </PrivateNotes>
                        <Quantity>10</Quantity>
                        <QuantityAvailable>10</QuantityAvailable>
                        <QuestionCount> long </QuestionCount>
                        <ReasonHideFromSearch> ReasonHideFromSearchCodeType </ReasonHideFromSearch>
                        <ReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ReservePrice>
                        <SellerProfiles>
                            <SellerPaymentProfile>
                                <PaymentProfileID> long </PaymentProfileID>
                                <PaymentProfileName> string </PaymentProfileName>
                            </SellerPaymentProfile>
                            <SellerReturnProfile>
                                <ReturnProfileID> long </ReturnProfileID>
                                <ReturnProfileName> string </ReturnProfileName>
                            </SellerReturnProfile>
                            <SellerShippingProfile>
                                <ShippingProfileID> long </ShippingProfileID>
                                <ShippingProfileName> string </ShippingProfileName>
                            </SellerShippingProfile>
                        </SellerProfiles>
                        <SellingStatus>
                            <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                            <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                            <QuantitySold>10</QuantitySold>
                        </SellingStatus>
                        <ShippingDetails>
                            <GlobalShipping> boolean </GlobalShipping>
                            <ShippingServiceOptions>
                                <LocalPickup> boolean </LocalPickup>
                                <ShippingServiceCost currencyID="CurrencyCodeType"> AmountType (double) </ShippingServiceCost>
                            </ShippingServiceOptions>
                            <!-- ... more ShippingServiceOptions nodes allowed here ... -->
                            <ShippingType> ShippingTypeCodeType </ShippingType>
                        </ShippingDetails>
                        <SKU> SKUType (string) </SKU>
                        <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                        <TimeLeft> duration </TimeLeft>
                        <Title> string </Title>
                        <Variations>
                            <Variation>
                                <Quantity>10</Quantity>
                                <SellingStatus>
                                    <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                                    <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                                    <QuantitySold>10</QuantitySold>
                                </SellingStatus>
                                <SKU> SKUType (string) </SKU>
                                <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                                <VariationSpecifics>
                                    <NameValueList>
                                        <Name> string </Name>
                                        <Value> string </Value>
                                        <!-- ... more Value values allowed here ... -->
                                    </NameValueList>
                                    <!-- ... more NameValueList nodes allowed here ... -->
                                </VariationSpecifics>
                                <!-- ... more VariationSpecifics nodes allowed here ... -->
                                <VariationTitle> string </VariationTitle>
                                <WatchCount> long </WatchCount>
                            </Variation>
                            <!-- ... more Variation nodes allowed here ... -->
                        </Variations>
                        <WatchCount> long </WatchCount>
                    </Item>
                    <OrderLineItemID> string </OrderLineItemID>
                    <PaidTime>2004-08-04T19:09:02.768Z</PaidTime>
                    <PaisaPayID> string </PaisaPayID>
                    <Platform> TransactionPlatformCodeType </Platform>
                    <QuantityPurchased>10</QuantityPurchased>
                    <SellerPaidStatus> PaidStatusCodeType </SellerPaidStatus>
                    <ShippedTime>2004-08-04T19:09:02.768Z</ShippedTime>
                    <Status>
                        <PaymentHoldStatus> PaymentHoldStatusCodeType </PaymentHoldStatus>
                    </Status>
                    <TotalPrice currencyID="CurrencyCodeType"> AmountType (double) </TotalPrice>
                    <TotalTransactionPrice currencyID="CurrencyCodeType"> AmountType (double) </TotalTransactionPrice>
                    <TransactionID> string </TransactionID>
                </Transaction>
            </OrderTransaction>
            <!-- ... more OrderTransaction nodes allowed here ... -->
        </OrderTransactionArray>
        <PaginationResult>
            <TotalNumberOfEntries>10</TotalNumberOfEntries>
            <TotalNumberOfPages>10</TotalNumberOfPages>
        </PaginationResult>
    </DeletedFromSoldList>
    <DeletedFromUnsoldList>
        <ItemArray>
            <Item>
                <BuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </BuyItNowPrice>
                <ClassifiedAdPayPerLeadFee currencyID="CurrencyCodeType"> AmountType (double) </ClassifiedAdPayPerLeadFee>
                <HideFromSearch> boolean </HideFromSearch>
                <ItemID> ItemIDType (string) </ItemID>
                <ListingDetails>
                    <ConvertedBuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedBuyItNowPrice>
                    <ConvertedReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedReservePrice>
                    <ConvertedStartPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedStartPrice>
                    <EndTime>2004-08-04T19:09:02.768Z</EndTime>
                    <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                </ListingDetails>
                <ListingType> ListingTypeCodeType </ListingType>
                <PictureDetails> PictureDetailsType </PictureDetails>
                <PrivateNotes> string </PrivateNotes>
                <Quantity>10</Quantity>
                <QuantityAvailable>10</QuantityAvailable>
                <QuestionCount> long </QuestionCount>
                <ReasonHideFromSearch> ReasonHideFromSearchCodeType </ReasonHideFromSearch>
                <ReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ReservePrice>
                <SellerProfiles>
                    <SellerPaymentProfile>
                        <PaymentProfileID> long </PaymentProfileID>
                        <PaymentProfileName> string </PaymentProfileName>
                    </SellerPaymentProfile>
                    <SellerReturnProfile>
                        <ReturnProfileID> long </ReturnProfileID>
                        <ReturnProfileName> string </ReturnProfileName>
                    </SellerReturnProfile>
                    <SellerShippingProfile>
                        <ShippingProfileID> long </ShippingProfileID>
                        <ShippingProfileName> string </ShippingProfileName>
                    </SellerShippingProfile>
                </SellerProfiles>
                <SellingStatus>
                    <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                    <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                    <QuantitySold>10</QuantitySold>
                </SellingStatus>
                <ShippingDetails>
                    <GlobalShipping> boolean </GlobalShipping>
                    <ShippingServiceOptions>
                        <LocalPickup> boolean </LocalPickup>
                        <ShippingServiceCost currencyID="CurrencyCodeType"> AmountType (double) </ShippingServiceCost>
                    </ShippingServiceOptions>
                    <!-- ... more ShippingServiceOptions nodes allowed here ... -->
                    <ShippingType> ShippingTypeCodeType </ShippingType>
                </ShippingDetails>
                <SKU> SKUType (string) </SKU>
                <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                <TimeLeft> duration </TimeLeft>
                <Title> string </Title>
                <Variations>
                    <Variation>
                        <PrivateNotes> string </PrivateNotes>
                        <Quantity>10</Quantity>
                        <SellingStatus>
                            <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                            <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                            <QuantitySold>10</QuantitySold>
                        </SellingStatus>
                        <SKU> SKUType (string) </SKU>
                        <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                        <VariationSpecifics>
                            <NameValueList>
                                <Name> string </Name>
                                <Value> string </Value>
                                <!-- ... more Value values allowed here ... -->
                            </NameValueList>
                            <!-- ... more NameValueList nodes allowed here ... -->
                        </VariationSpecifics>
                        <!-- ... more VariationSpecifics nodes allowed here ... -->
                        <VariationTitle> string </VariationTitle>
                        <WatchCount> long </WatchCount>
                    </Variation>
                    <!-- ... more Variation nodes allowed here ... -->
                </Variations>
                <WatchCount> long </WatchCount>
            </Item>
            <!-- ... more Item nodes allowed here ... -->
        </ItemArray>
        <PaginationResult>
            <TotalNumberOfEntries>10</TotalNumberOfEntries>
            <TotalNumberOfPages>10</TotalNumberOfPages>
        </PaginationResult>
    </DeletedFromUnsoldList>
    <ScheduledList>
        <ItemArray>
            <Item>
                <BuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </BuyItNowPrice>
                <ClassifiedAdPayPerLeadFee currencyID="CurrencyCodeType"> AmountType (double) </ClassifiedAdPayPerLeadFee>
                <eBayNotes> string </eBayNotes>
                <HideFromSearch> boolean </HideFromSearch>
                <ItemID> ItemIDType (string) </ItemID>
                <ListingDetails>
                    <ConvertedBuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedBuyItNowPrice>
                    <ConvertedReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedReservePrice>
                    <ConvertedStartPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedStartPrice>
                    <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                </ListingDetails>
                <ListingDuration> token </ListingDuration>
                <ListingType> ListingTypeCodeType </ListingType>
                <PictureDetails> PictureDetailsType </PictureDetails>
                <PrivateNotes> string </PrivateNotes>
                <Quantity>10</Quantity>
                <QuantityAvailable>10</QuantityAvailable>
                <QuestionCount> long </QuestionCount>
                <ReasonHideFromSearch> ReasonHideFromSearchCodeType </ReasonHideFromSearch>
                <ReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ReservePrice>
                <SellerProfiles>
                    <SellerPaymentProfile>
                        <PaymentProfileID> long </PaymentProfileID>
                        <PaymentProfileName> string </PaymentProfileName>
                    </SellerPaymentProfile>
                    <SellerReturnProfile>
                        <ReturnProfileID> long </ReturnProfileID>
                        <ReturnProfileName> string </ReturnProfileName>
                    </SellerReturnProfile>
                    <SellerShippingProfile>
                        <ShippingProfileID> long </ShippingProfileID>
                        <ShippingProfileName> string </ShippingProfileName>
                    </SellerShippingProfile>
                </SellerProfiles>
                <SellingStatus>
                    <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                    <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                    <QuantitySold>10</QuantitySold>
                    <ReserveMet> boolean </ReserveMet>
                </SellingStatus>
                <ShippingDetails>
                    <GlobalShipping> boolean </GlobalShipping>
                    <ShippingServiceOptions>
                        <LocalPickup> boolean </LocalPickup>
                        <ShippingServiceCost currencyID="CurrencyCodeType"> AmountType (double) </ShippingServiceCost>
                        <ShippingSurcharge currencyID="CurrencyCodeType"> AmountType (double) </ShippingSurcharge>
                    </ShippingServiceOptions>
                    <!-- ... more ShippingServiceOptions nodes allowed here ... -->
                    <ShippingType> ShippingTypeCodeType </ShippingType>
                </ShippingDetails>
                <SKU> SKUType (string) </SKU>
                <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                <TimeLeft> duration </TimeLeft>
                <Title> string </Title>
                <Variations>
                    <Variation>
                        <PrivateNotes> string </PrivateNotes>
                        <Quantity>10</Quantity>
                        <SellingStatus>
                            <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                            <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                            <QuantitySold>10</QuantitySold>
                            <ReserveMet> boolean </ReserveMet>
                        </SellingStatus>
                        <SKU> SKUType (string) </SKU>
                        <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                        <VariationSpecifics>
                            <NameValueList>
                                NameValueListType
                                <Name> string </Name>
                                <Value> string </Value>
                                <!-- ... more Value values allowed here ... -->
                            </NameValueList>
                            <!-- ... more NameValueList nodes allowed here ... -->
                        </VariationSpecifics>
                        <!-- ... more VariationSpecifics nodes allowed here ... -->
                        <VariationTitle> string </VariationTitle>
                        <WatchCount> long </WatchCount>
                    </Variation>
                    <!-- ... more Variation nodes allowed here ... -->
                </Variations>
                <WatchCount> long </WatchCount>
            </Item>
            <!-- ... more Item nodes allowed here ... -->
        </ItemArray>
        <PaginationResult>
            <TotalNumberOfEntries>10</TotalNumberOfEntries>
            <TotalNumberOfPages>10</TotalNumberOfPages>
        </PaginationResult>
    </ScheduledList>
    <SellingSummary>
        <ActiveAuctionCount>10</ActiveAuctionCount>
        <AuctionBidCount>10</AuctionBidCount>
        <AuctionSellingCount>10</AuctionSellingCount>
        <SoldDurationInDays>10</SoldDurationInDays>
        <TotalAuctionSellingValue currencyID="CurrencyCodeType"> AmountType (double) </TotalAuctionSellingValue>
        <TotalSoldCount>10</TotalSoldCount>
        <TotalSoldValue currencyID="CurrencyCodeType"> AmountType (double) </TotalSoldValue>
    </SellingSummary>
    <SoldList>
        <OrderTransactionArray>
            <OrderTransaction>
                <Order>
                    <OrderID>123456</OrderID>
                    <RefundAmount currencyID="CurrencyCodeType"> AmountType (double) </RefundAmount>
                    <RefundStatus> string </RefundStatus>
                    <Subtotal currencyID="CurrencyCodeType"> AmountType (double) </Subtotal>
                    <TransactionArray>
                        <Transaction>
                            <Buyer>
                                <BuyerInfo>
                                    <ShippingAddress>
                                        <PostalCode> string </PostalCode>
                                    </ShippingAddress>
                                </BuyerInfo>
                                <Email> string </Email>
                                <StaticAlias> string </StaticAlias>
                                <UserID>1235766</UserID>
                            </Buyer>
                            <ConvertedTransactionPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedTransactionPrice>
                            <CreatedDate>2004-08-04T19:09:02.768Z</CreatedDate>
                            <FeedbackLeft>
                                <CommentType> CommentTypeCodeType </CommentType>
                            </FeedbackLeft>
                            <FeedbackReceived>
                                <CommentType> CommentTypeCodeType </CommentType>
                            </FeedbackReceived>
                            <IsMultiLegShipping> boolean </IsMultiLegShipping>
                            <Item>
                                <BuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </BuyItNowPrice>
                                <ClassifiedAdPayPerLeadFee currencyID="CurrencyCodeType"> AmountType (double) </ClassifiedAdPayPerLeadFee>
                                <HideFromSearch> boolean </HideFromSearch>
                                <ItemID> ItemIDType (string) </ItemID>
                                <ListingDetails>
                                    <ConvertedBuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedBuyItNowPrice>
                                    <ConvertedReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedReservePrice>
                                    <ConvertedStartPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedStartPrice>
                                    <EndTime>2004-08-04T19:09:02.768Z</EndTime>
                                    <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                                </ListingDetails>
                                <ListingType> ListingTypeCodeType </ListingType>
                                <PictureDetails> PictureDetailsType </PictureDetails>
                                <PrivateNotes> string </PrivateNotes>
                                <Quantity>10</Quantity>
                                <QuantityAvailable>10</QuantityAvailable>
                                <QuestionCount> long </QuestionCount>
                                <ReasonHideFromSearch> ReasonHideFromSearchCodeType </ReasonHideFromSearch>
                                <ReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ReservePrice>
                                <SellerProfiles>
                                    <SellerPaymentProfile>
                                        <PaymentProfileID> long </PaymentProfileID>
                                        <PaymentProfileName> string </PaymentProfileName>
                                    </SellerPaymentProfile>
                                    <SellerReturnProfile>
                                        <ReturnProfileID> long </ReturnProfileID>
                                        <ReturnProfileName> string </ReturnProfileName>
                                    </SellerReturnProfile>
                                    <SellerShippingProfile>
                                        <ShippingProfileID> long </ShippingProfileID>
                                        <ShippingProfileName> string </ShippingProfileName>
                                    </SellerShippingProfile>
                                </SellerProfiles>
                                <SellingStatus>
                                    <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                                    <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                                    <QuantitySold>10</QuantitySold>
                                    <ReserveMet> boolean </ReserveMet>
                                </SellingStatus>
                                <ShippingDetails>
                                    <GlobalShipping> boolean </GlobalShipping>
                                    <ShippingServiceOptions>
                                        <LocalPickup> boolean </LocalPickup>
                                        <ShippingServiceCost currencyID="CurrencyCodeType"> AmountType (double) </ShippingServiceCost>
                                    </ShippingServiceOptions>
                                    <!-- ... more ShippingServiceOptions nodes allowed here ... -->
                                    <ShippingType> ShippingTypeCodeType </ShippingType>
                                </ShippingDetails>
                                <SKU> SKUType (string) </SKU>
                                <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                                <TimeLeft> duration </TimeLeft>
                                <Title> string </Title>
                                <Variations>
                                    <Variation>
                                        <Quantity>10</Quantity>
                                        <SellingStatus>
                                            <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                                            <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                                            <QuantitySold>10</QuantitySold>
                                            <ReserveMet> boolean </ReserveMet>
                                        </SellingStatus>
                                        <SKU> SKUType (string) </SKU>
                                        <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                                        <VariationSpecifics>
                                            <NameValueList>
                                                <Name> string </Name>
                                                <Value> string </Value>
                                                <!-- ... more Value values allowed here ... -->
                                            </NameValueList>
                                            <!-- ... more NameValueList nodes allowed here ... -->
                                        </VariationSpecifics>
                                        <!-- ... more VariationSpecifics nodes allowed here ... -->
                                        <VariationTitle> string </VariationTitle>
                                        <WatchCount> long </WatchCount>
                                    </Variation>
                                    <!-- ... more Variation nodes allowed here ... -->
                                </Variations>
                                <WatchCount> long </WatchCount>
                            </Item>
                            <OrderLineItemID> string </OrderLineItemID>
                            <PaidTime>2004-08-04T19:09:02.768Z</PaidTime>
                            <PaisaPayID> string </PaisaPayID>
                            <PaymentHoldDetails>
                                <ExpectedReleaseDate>2004-08-04T19:09:02.768Z</ExpectedReleaseDate>
                                <PaymentHoldReason> PaymentHoldReasonCodeType </PaymentHoldReason>
                                <RequiredSellerActionArray>
                                    <RequiredSellerAction> RequiredSellerActionCodeType </RequiredSellerAction>
                                    <!-- ... more RequiredSellerAction values allowed here ... -->
                                </RequiredSellerActionArray>
                            </PaymentHoldDetails>
                            <Platform> TransactionPlatformCodeType </Platform>
                            <QuantityPurchased>10</QuantityPurchased>
                            <SellerPaidStatus> PaidStatusCodeType </SellerPaidStatus>
                            <ShippedTime>2004-08-04T19:09:02.768Z</ShippedTime>
                            <Status>
                                <PaymentHoldStatus> PaymentHoldStatusCodeType </PaymentHoldStatus>
                            </Status>
                            <TotalPrice currencyID="CurrencyCodeType"> AmountType (double) </TotalPrice>
                            <TotalTransactionPrice currencyID="CurrencyCodeType"> AmountType (double) </TotalTransactionPrice>
                            <TransactionID> string </TransactionID>
                        </Transaction>
                        <!-- ... more Transaction nodes allowed here ... -->
                    </TransactionArray>
                </Order>
                <Transaction>
                    <Buyer>
                        <BuyerInfo>
                            <ShippingAddress>
                                <PostalCode> string </PostalCode>
                            </ShippingAddress>
                        </BuyerInfo>
                        <Email> string </Email>
                        <StaticAlias> string </StaticAlias>
                        <UserID>1235766</UserID>
                    </Buyer>
                    <ConvertedTransactionPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedTransactionPrice>
                    <CreatedDate>2004-08-04T19:09:02.768Z</CreatedDate>
                    <FeedbackLeft>
                        <CommentType> CommentTypeCodeType </CommentType>
                    </FeedbackLeft>
                    <FeedbackReceived>
                        <CommentType> CommentTypeCodeType </CommentType>
                    </FeedbackReceived>
                    <IsMultiLegShipping> boolean </IsMultiLegShipping>
                    <Item>
                        <BuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </BuyItNowPrice>
                        <ClassifiedAdPayPerLeadFee currencyID="CurrencyCodeType"> AmountType (double) </ClassifiedAdPayPerLeadFee>
                        <HideFromSearch> boolean </HideFromSearch>
                        <ItemID> ItemIDType (string) </ItemID>
                        <ListingDetails>
                            <ConvertedBuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedBuyItNowPrice>
                            <ConvertedReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedReservePrice>
                            <ConvertedStartPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedStartPrice>
                            <EndTime>2004-08-04T19:09:02.768Z</EndTime>
                            <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                        </ListingDetails>
                        <ListingType> ListingTypeCodeType </ListingType>
                        <PictureDetails> PictureDetailsType </PictureDetails>
                        <PrivateNotes> string </PrivateNotes>
                        <Quantity>10</Quantity>
                        <QuantityAvailable>10</QuantityAvailable>
                        <QuestionCount> long </QuestionCount>
                        <ReasonHideFromSearch> ReasonHideFromSearchCodeType </ReasonHideFromSearch>
                        <ReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ReservePrice>
                        <SellerProfiles>
                            <SellerPaymentProfile>
                                <PaymentProfileID> long </PaymentProfileID>
                                <PaymentProfileName> string </PaymentProfileName>
                            </SellerPaymentProfile>
                            <SellerReturnProfile>
                                <ReturnProfileID> long </ReturnProfileID>
                                <ReturnProfileName> string </ReturnProfileName>
                            </SellerReturnProfile>
                            <SellerShippingProfile>
                                <ShippingProfileID> long </ShippingProfileID>
                                <ShippingProfileName> string </ShippingProfileName>
                            </SellerShippingProfile>
                        </SellerProfiles>
                        <SellingStatus>
                            <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                            <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                            <QuantitySold>10</QuantitySold>
                            <ReserveMet> boolean </ReserveMet>
                        </SellingStatus>
                        <ShippingDetails>
                            <GlobalShipping> boolean </GlobalShipping>
                            <ShippingServiceOptions>
                                <LocalPickup> boolean </LocalPickup>
                                <ShippingServiceCost currencyID="CurrencyCodeType"> AmountType (double) </ShippingServiceCost>
                            </ShippingServiceOptions>
                            <!-- ... more ShippingServiceOptions nodes allowed here ... -->
                            <ShippingType> ShippingTypeCodeType </ShippingType>
                        </ShippingDetails>
                        <SKU> SKUType (string) </SKU>
                        <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                        <TimeLeft> duration </TimeLeft>
                        <Title> string </Title>
                        <Variations>
                            <Variation>
                                <Quantity>10</Quantity>
                                <SellingStatus>
                                    <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                                    <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                                    <QuantitySold>10</QuantitySold>
                                    <ReserveMet> boolean </ReserveMet>
                                </SellingStatus>
                                <SKU> SKUType (string) </SKU>
                                <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                                <VariationSpecifics>
                                    <NameValueList>
                                        <Name> string </Name>
                                        <Value> string </Value>
                                        <!-- ... more Value values allowed here ... -->
                                    </NameValueList>
                                    <!-- ... more NameValueList nodes allowed here ... -->
                                </VariationSpecifics>
                                <!-- ... more VariationSpecifics nodes allowed here ... -->
                                <VariationTitle> string </VariationTitle>
                                <WatchCount> long </WatchCount>
                            </Variation>
                            <!-- ... more Variation nodes allowed here ... -->
                        </Variations>
                        <WatchCount> long </WatchCount>
                    </Item>
                    <OrderLineItemID> string </OrderLineItemID>
                    <PaidTime>2004-08-04T19:09:02.768Z</PaidTime>
                    <PaisaPayID> string </PaisaPayID>
                    <PaymentHoldDetails>
                        <ExpectedReleaseDate>2004-08-04T19:09:02.768Z</ExpectedReleaseDate>
                        <PaymentHoldReason> PaymentHoldReasonCodeType </PaymentHoldReason>
                        <RequiredSellerActionArray>
                            RequiredSellerActionArrayType
                            <RequiredSellerAction> RequiredSellerActionCodeType </RequiredSellerAction>
                            <!-- ... more RequiredSellerAction values allowed here ... -->
                        </RequiredSellerActionArray>
                    </PaymentHoldDetails>
                    <Platform> TransactionPlatformCodeType </Platform>
                    <QuantityPurchased>10</QuantityPurchased>
                    <SellerPaidStatus> PaidStatusCodeType </SellerPaidStatus>
                    <ShippedTime>2004-08-04T19:09:02.768Z</ShippedTime>
                    <Status>
                        <PaymentHoldStatus> PaymentHoldStatusCodeType </PaymentHoldStatus>
                    </Status>
                    <TotalPrice currencyID="CurrencyCodeType"> AmountType (double) </TotalPrice>
                    <TotalTransactionPrice currencyID="CurrencyCodeType"> AmountType (double) </TotalTransactionPrice>
                    <TransactionID> string </TransactionID>
                </Transaction>
            </OrderTransaction>
            <!-- ... more OrderTransaction nodes allowed here ... -->
        </OrderTransactionArray>
        <PaginationResult>
            <TotalNumberOfEntries>10</TotalNumberOfEntries>
            <TotalNumberOfPages>10</TotalNumberOfPages>
        </PaginationResult>
    </SoldList>
    <Summary>
        <ActiveAuctionCount>10</ActiveAuctionCount>
        <AmountLimitRemaining currencyID="CurrencyCodeType"> AmountType (double) </AmountLimitRemaining>
        <AuctionBidCount>10</AuctionBidCount>
        <AuctionSellingCount>10</AuctionSellingCount>
        <ClassifiedAdCount>10</ClassifiedAdCount>
        <ClassifiedAdOfferCount>10</ClassifiedAdOfferCount>
        <QuantityLimitRemaining> long </QuantityLimitRemaining>
        <SoldDurationInDays>10</SoldDurationInDays>
        <TotalAuctionSellingValue currencyID="CurrencyCodeType"> AmountType (double) </TotalAuctionSellingValue>
        <TotalLeadCount>10</TotalLeadCount>
        <TotalListingsWithLeads>10</TotalListingsWithLeads>
        <TotalSoldCount>10</TotalSoldCount>
        <TotalSoldValue currencyID="CurrencyCodeType"> AmountType (double) </TotalSoldValue>
    </Summary>
    <UnsoldList>
        <ItemArray>
            <Item>
                <BuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </BuyItNowPrice>
                <ClassifiedAdPayPerLeadFee currencyID="CurrencyCodeType"> AmountType (double) </ClassifiedAdPayPerLeadFee>
                <eBayNotes> string </eBayNotes>
                <HideFromSearch> boolean </HideFromSearch>
                <ItemID> ItemIDType (string) </ItemID>
                <LeadCount>10</LeadCount>
                <ListingDetails>
                    <ConvertedBuyItNowPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedBuyItNowPrice>
                    <ConvertedReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedReservePrice>
                    <ConvertedStartPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedStartPrice>
                    <EndTime>2004-08-04T19:09:02.768Z</EndTime>
                    <StartTime>2004-08-04T19:09:02.768Z</StartTime>
                </ListingDetails>
                <ListingDuration> token </ListingDuration>
                <ListingType> ListingTypeCodeType </ListingType>
                <PictureDetails> PictureDetailsType </PictureDetails>
                <PrivateNotes> string </PrivateNotes>
                <Quantity>10</Quantity>
                <QuantityAvailable>10</QuantityAvailable>
                <QuestionCount> long </QuestionCount>
                <ReasonHideFromSearch> ReasonHideFromSearchCodeType </ReasonHideFromSearch>
                <Relisted> boolean </Relisted>
                <ReservePrice currencyID="CurrencyCodeType"> AmountType (double) </ReservePrice>
                <SellerProfiles>
                    <SellerPaymentProfile>
                        <PaymentProfileID> long </PaymentProfileID>
                        <PaymentProfileName> string </PaymentProfileName>
                    </SellerPaymentProfile>
                    <SellerReturnProfile>
                        <ReturnProfileID> long </ReturnProfileID>
                        <ReturnProfileName> string </ReturnProfileName>
                    </SellerReturnProfile>
                    <SellerShippingProfile>
                        <ShippingProfileID> long </ShippingProfileID>
                        <ShippingProfileName> string </ShippingProfileName>
                    </SellerShippingProfile>
                </SellerProfiles>
                <SellingStatus>
                    <BidCount>10</BidCount>
                    <BidderCount> long </BidderCount>
                    <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                    <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                    <QuantitySold>10</QuantitySold>
                    <ReserveMet> boolean </ReserveMet>
                </SellingStatus>
                <ShippingDetails>
                    <GlobalShipping> boolean </GlobalShipping>
                    <ShippingServiceOptions>
                        <LocalPickup> boolean </LocalPickup>
                        <ShippingServiceCost currencyID="CurrencyCodeType"> AmountType (double) </ShippingServiceCost>
                    </ShippingServiceOptions>
                    <!-- ... more ShippingServiceOptions nodes allowed here ... -->
                    <ShippingType> ShippingTypeCodeType </ShippingType>
                </ShippingDetails>
                <SKU> SKUType (string) </SKU>
                <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                <TimeLeft> duration </TimeLeft>
                <Title> string </Title>
                <Variations>
                    <Variation>
                        <PrivateNotes> string </PrivateNotes>
                        <Quantity>10</Quantity>
                        <SellingStatus>
                            <BidCount>10</BidCount>
                            <BidderCount> long </BidderCount>
                            <ConvertedCurrentPrice currencyID="CurrencyCodeType"> AmountType (double) </ConvertedCurrentPrice>
                            <CurrentPrice currencyID="CurrencyCodeType">113434</CurrentPrice>
                            <QuantitySold>10</QuantitySold>
                            <ReserveMet> boolean </ReserveMet>
                        </SellingStatus>
                        <SKU> SKUType (string) </SKU>
                        <StartPrice currencyID="CurrencyCodeType"> AmountType (double) </StartPrice>
                        <VariationSpecifics>
                            <NameValueList>
                                <Name> string </Name>
                                <Value> string </Value>
                                <!-- ... more Value values allowed here ... -->
                            </NameValueList>
                            <!-- ... more NameValueList nodes allowed here ... -->
                        </VariationSpecifics>
                        <!-- ... more VariationSpecifics nodes allowed here ... -->
                        <VariationTitle> string </VariationTitle>
                        <WatchCount> long </WatchCount>
                    </Variation>
                    <!-- ... more Variation nodes allowed here ... -->
                </Variations>
                <WatchCount> long </WatchCount>
            </Item>
            <!-- ... more Item nodes allowed here ... -->
        </ItemArray>
        <PaginationResult>
            <TotalNumberOfEntries>10</TotalNumberOfEntries>
            <TotalNumberOfPages>10</TotalNumberOfPages>
        </PaginationResult>
    </UnsoldList>
    <!-- Standard Output Fields -->
    <Ack> AckCodeType </Ack>
    <Build> string </Build>
    <CorrelationID> string </CorrelationID>
    <Errors>
        <ErrorClassification> ErrorClassificationCodeType </ErrorClassification>
        <ErrorCode> token </ErrorCode>
        <ErrorParameters ParamID="string">
            <Value> string </Value>
        </ErrorParameters>
        <!-- ... more ErrorParameters nodes allowed here ... -->
        <LongMessage> string </LongMessage>
        <SeverityCode> SeverityCodeType </SeverityCode>
        <ShortMessage> string </ShortMessage>
    </Errors>
    <!-- ... more Errors nodes allowed here ... -->
    <HardExpirationWarning> string </HardExpirationWarning>
    <Timestamp>2004-08-04T19:09:02.768Z</Timestamp>
    <Version> string </Version>
</GetMyeBaySellingResponse>';
        $xml    = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
        $json   = json_encode($xml);
        return json_decode($json, true);
    }

    /**
     * excute export csv
     * @param  string $fileName
     * @param  array $columns
     * @param  array $rowList
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function excuteExportCsv($fileName, $columns, $rowList)
    {
        try {
            $headers = array(
                'Content-type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=' . $fileName,
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            );

            $callback = function () use ($columns, $rowList) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, $columns);

                foreach ($rowList as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            logger(__METHOD__ . ': ' . $e->getMessage());
            abort('500');
        }
    }

    /**
     * get policy name by id
     * @param  integer $id
     * @param  array $settingPolicyData
     * @return string
     */
    public function getPolicyNameById($id, $settingPolicyData)
    {
        foreach ($settingPolicyData as $key => $policy) {
            if ($policy->id == $id) {
                return $policy->policy_name;
            }
        }
        return null;
    }

    /**
     * get data setting policies
     * @return array
     */
    public function getDataSettingPolicies()
    {
        $userId            = Auth::user()->id;
        $settingPolicyData = $this->settingPolicy->getSettingPolicyOfUser($userId);
        $shippingType[null] = null;
        $paymentType[null]  = null;
        $returnType[null]   = null;
        foreach ($settingPolicyData as $key => $policy) {
            if ($policy->policy_type == SettingPolicy::TYPE_SHIPPING) {
                $shippingType[$policy->id] = $policy->policy_name;
            } elseif ($policy->policy_type == SettingPolicy::TYPE_PAYMENT) {
                $paymentType[$policy->id] = $policy->policy_name;
            } else {
                $returnType[$policy->id] = $policy->policy_name;
            }
        }
        return [
            'shipping' => $shippingType,
            'payment'  => $paymentType,
            'return'   => $returnType
        ];
    }

    /**
     * get setting shipping of user
     * @param  array $input
     * @return array
     */
    public function getSettingShippingOfUser($input)
    {
        $height                = !empty($input['height']) ? $input['height'] : 0;
        $width                 = !empty($input['width']) ? $input['width'] : 0;
        $length                = !empty($input['length']) ? $input['length'] : 0;
        $sizeOfProduct         = $length + $height + $width;
        $userId                = Auth::user()->id;
        $settingShipping       = $this->settingShipping->getSettingShippingOfUser($userId);
        $settingShippingOption[null] = null;
        foreach ($settingShipping as $key => $item) {
            $sideMaxSize = $item->side_max_size;
            if ($sizeOfProduct <= $item->max_size &&
                $height < $sideMaxSize &&
                $length <= $sideMaxSize &&
                $width <= $sideMaxSize
            ) {
                $settingShippingOption[$item->id] = $item->shipping_name;
            }
        }
        if (count($settingShippingOption) == 1) {
            $settingShipping = $this->settingShipping->findSettingShippingMaxSizeOfUser($userId);
            $settingShippingOption[$settingShipping->id] = $settingShipping->shipping_name;
        }
        return $settingShippingOption;
    }

    /**
     * format store info
     * @param  array $stores
     * @return array
     */
    public function formatStoreInfo($stores)
    {
        $arrayCategoryFee = ['standard_fee_rate', 'basic_fee_rate', 'premium_fee_rate', 'anchor_fee_rate'];
        $result = [];
        foreach ($stores as $key => $store) {
            $result[$store->id] = $arrayCategoryFee[$key];
        }
        return $result;
    }

    /**
     * format data page product
     * @param  array $data
     * @return array
     */
    public function formatDataPageProduct($data)
    {
        $data['duration']['option']      = $this->product->getDurationOption();
        $data['duration']['value']       = $data['dtb_item']['duration'];
        $settingShippingOption           = $this->getSettingShippingOfUser($data['dtb_item']);
        $data['setting_shipping_option'] = $settingShippingOption;
        $data['setting_shipping_selected'] = $data['dtb_item']['temp_shipping_method'];
        $data['dtb_setting_policies']    = $this->getDataSettingPolicies();
        $userId                          = Auth::user()->id;
        $settingTemplate                 = $this->settingTemplate->getByUserId($userId);
        $data['setting_template']        = $this->formatSettingTemplate($settingTemplate);
        return $data;
    }

    /**
     * format setting template
     * @param  array $settingTemplate
     * @return array
     */
    public function formatSettingTemplate($settingTemplate)
    {
        $result[null] = null;
        foreach ($settingTemplate as $item) {
            $result[$item['id']] = $item['title'];
        }
        return $result;
    }

    /**
     * calculator profit detail
     * @param  array $data
     * @return void
     */
    public function calculatorDetail(&$data)
    {
        $exchangeRate                   = $this->exchangeRate->getExchangeRateLatest();
        $userId                         = Auth::user()->id;
        $settingInfo                    = $this->setting->getSettingOfUser($userId);
        $storeIdOfUser                  = $settingInfo->store_id;
        $stores                         = $this->mtbStore->getAllStore();
        $storeInfo                      = $this->formatStoreInfo($stores);
        $typeFee                        = $storeInfo[$storeIdOfUser];
        $sellPriceYen                   = round($data['dtb_item']['price'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['ebay_fee']   = round($data['dtb_item']['price'] * $this->categoryFee->getCategoryFeeByCategoryId($data['dtb_item']['category_id'])->$typeFee / 100, 2);
        $ebayFeeYen                     = round($data['dtb_item']['ebay_fee'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['paypal_fee'] = round($settingInfo->paypal_fee_rate  * $sellPriceYen / 100 + $settingInfo->paypal_fixed_fee, 2);
        if ($data['istTypeAmazon']) {
            $data['dtb_item']['profit'] = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - $data['dtb_item']['ship_fee'] - (float)$data['dtb_item']['buy_price'] * $settingInfo->gift_discount / 100, 2);
        } else {
            $data['dtb_item']['profit'] = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - $data['dtb_item']['ship_fee'] - (float)$data['dtb_item']['buy_price'], 2);
        }
    }

    /**
     * create date
     * @param  string $date
     * @return string
     */
    public function createDate($date)
    {
        $date = date_create($date);
        return date_format($date, "Y-m-d H:i:s");
    }
}
