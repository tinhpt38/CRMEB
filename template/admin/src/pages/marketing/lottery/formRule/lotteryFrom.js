const createLotteryRules = (t) => ({
  name: [{ required: true, message: t('message.marketing.lottery.pleaseInputActivityName'), trigger: 'blur' }],
  factor: [{ required: true, type: 'number', message: t('message.marketing.lottery.selectActivityType'), trigger: 'change' }],
  attends_user: [{ required: true, type: 'number', message: t('message.marketing.lottery.selectParticipants'), trigger: 'change' }],
  factor_num: [{ required: true, type: 'number', message: t('message.marketing.lottery.pleaseInputLotteryCount'), trigger: 'blur' }],
  prize: [
    {
      required: true,
      type: 'array',
      message: t('message.marketing.lottery.pleaseAddPrizes'),
      trigger: 'change',
    },
    {
      type: 'array',
      min: 8,
      message: t('message.marketing.lottery.pleaseAddPrizes'),
      trigger: 'change',
    },
  ],
  lottery_num: [
    {
      required: true,
      type: 'number',
      message: t('message.marketing.lottery.pleaseInputInviteLotteryCount'),
      trigger: 'blur',
    },
  ],
  spread_num: [
    {
      required: true,
      type: 'number',
      message: t('message.marketing.lottery.pleaseInputFollowLotteryCount'),
      trigger: 'blur',
    },
  ],
  image: [
    {
      required: true,
      message: t('message.marketing.lottery.pleaseUploadBackground'),
      trigger: 'change',
    },
  ],
  content: [
    {
      required: true,
      message: t('message.marketing.lottery.pleaseInputRules'),
      trigger: 'blur',
    },
  ],
});

export { createLotteryRules };
