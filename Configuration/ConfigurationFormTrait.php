<?php
namespace MCPS\Helper\Configuration;

trait ConfigurationFormTrait
{

    use ConfigurationTrait;

    private function mapInputType($type)
    {
        $map = [
            'switch' => 'boolean',
            'textarea' => 'string',
        ];
        return \array_search($type, $map);
    }

    public final function getFormInputs()
    {
        $inputs = [];
        foreach ($this->getConfig() as $configKey => $configValue) {
            $type = $this->mapInputType(\gettype($configValue));
            switch ($type) {
                case 'switch':
                    $values = [
                        [
                            'id' => 'active_on',
                            'value' => true,
                            'label' => $this->l('Enabled')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => false,
                            'label' => $this->l('Disabled')
                        ]
                    ];

                    break;
                default:
                    $values = [];
                    break;
            }
            $input = [
                'type' => $type,
                'name' => $configKey,
                'label' => $this->l($configKey),
                'lang' => false,
                'is_bool' => is_bool($configValue),
                'values' => $values,
            ];
            \array_push($inputs, $input);
        }
        return $inputs;
    }

    public final function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => $this->getFormInputs(),
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    public final function getSubmitAction()
    {
        return 'submit' . $this->name;
    }

    public function getContent()
    {
        if (((bool) \Tools::isSubmit($this->getSubmitAction())) == true) {
            $this->postProcess();
        }

        return $this->renderForm();
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfig();
        foreach (array_keys($form_values) as $key) {
            $form_values[$key] = \Tools::getValue($key);
        }
        $this->setConfig($form_values);
    }
}
