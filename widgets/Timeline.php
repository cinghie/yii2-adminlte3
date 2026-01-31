<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-adminlte3
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 * @package yii2-AdminLTE
 * @version 0.1.0
 */

namespace cinghie\adminlte3\widgets;

use Yii;
use cinghie\commerce\models\Accounts;
use cinghie\commerce\models\Carrier;
use cinghie\commerce\models\Category as ProductCategory;
use cinghie\commerce\models\Contacts;
use cinghie\commerce\models\Currency;
use cinghie\commerce\models\Entry;
use cinghie\commerce\models\Expense;
use cinghie\commerce\models\Manufacturer;
use cinghie\commerce\models\Order;
use cinghie\commerce\models\Payment;
use cinghie\commerce\models\PaymentMethod;
use cinghie\commerce\models\Product;
use cinghie\commerce\models\ProductAttribute;
use cinghie\commerce\models\Quote;
use cinghie\commerce\models\Shop;
use cinghie\commerce\models\Tax;
use cinghie\userextended\models\User;
use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Timeline widget for AdminLTE 3.
 * All dynamic text is HTML-encoded to prevent XSS. Icon is sanitized (alphanumeric, space, hyphen only).
 */
class Timeline extends Widget
{
    /** @var string[] Allowed CSS class prefix for icon (e.g. fas, far, fab). Used to whitelist icon. */
    public static $allowedIconPrefixes = ['fas', 'far', 'fab', 'fa', 'fa-'];

    /**
     * Sanitize icon string for use in class attribute (allow only safe CSS class chars).
     * @param string|null $icon
     * @return string
     */
    protected static function sanitizeIconClass($icon)
    {
        if ($icon === null || $icon === '') {
            return 'fas fa-circle';
        }
        return preg_replace('/[^\w\s\-]/', '', $icon) ?: 'fas fa-circle';
    }
    /** @var array */
    public array $days;

    /** @var array */
    public array $items;

    /**
     * Optional map user_id => username to avoid N+1 queries. If set, User::find() is not called per item.
     * Example: User::find()->select(['id','username'])->indexBy('id')->column() then pass as ['id'=>'username', ...]
     * @var array<int,string>
     */
    public $userNames = [];

    /**
     * @param $day
     * @return string
     */
    public function timelineItem($day)
    {
        $html = '';

        foreach ($this->items as $item)
        {
            if(isset($item) && $item->created_date === $day)
            {
                if (isset($this->userNames[$item->created_by])) {
                    $username = Html::encode($this->userNames[$item->created_by]);
                } else {
                    $user = User::find()->where(['id'=> $item->created_by])->one();
                    $username = $user ? Html::encode($user->username) : '';
                }
                $userurl = Html::encode(Url::toRoute(['/logger/loggers/timeline', 'user_id' => $item->created_by]));
                $iconClass = self::sanitizeIconClass($item->icon ?? null);

                switch ($item->action)
                {
                    case 'create':
                        $bgColor = ' color-create';
                        break;
                    case 'update':
                        $bgColor = ' color-update';
                        break;
                    case 'delete':
                        $bgColor = ' color-delete';
                        break;
                    default:
                        $bgColor = '';
                }

                $html .= '<div class=""><i class="'.$iconClass.$bgColor.'"></i>';
                $html .= '<div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> '.Html::encode(substr($item->created_time, 0, 5)).'</span>';
                $html .= '<h3 class="timeline-header"><a href="'.$userurl.'">'.$username.'</a> ';
                $html .= Html::encode(Yii::t('traits', $item->action)).' <strong>'.Html::encode($item->entity_name).'</strong></h3>';

                $html .= '<div class="timeline-body">';

                switch ($item->entity_model)
                {
                    case 'Attribute':

                        $elementModel = new ProductAttribute();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->getCombinationName());
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Accounts':

                        $elementModel = new Accounts();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Carrier':

                        $elementModel = new Carrier();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Category':

                        $elementModel = new ProductCategory();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Contacts':

                        $elementModel = new Contacts();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->getFullName());
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Currency':

                        $elementModel = new Currency();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Entry':

                        $elementModel = new Entry();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Expense':

                        $elementModel = new Expense();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Manufacturer':

                        $elementModel = new Manufacturer();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Order':

                        $elementModel = new Order();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->reference);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Payment':

                        $elementModel = new Payment();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->reference);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'PaymentMethod':

                        $elementModel = new PaymentMethod();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Product':

                        $elementModel = new Product();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

	                case 'Quote':

		                $elementModel = new Quote();
		                $element = $elementModel::findOne($item->entity_id);
		                $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

		                if($element) {
			                $enc = Html::encode($element->reference);
			                $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
		                } else {
			                $html .= Html::encode($item->data ?? '');
		                }

		                break;

                    case 'Shop':

                        $elementModel = new Shop();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;

                    case 'Tax':

                        $elementModel = new Tax();
                        $element = $elementModel::findOne($item->entity_id);
                        $url = Html::encode(Url::toRoute([$item->entity_url, 'id' => $item->entity_id]));

                        if($element) {
                            $enc = Html::encode($element->name);
                            $html .= '<a href="'.$url.'" title="'.$enc.'">'.$enc.'</a>';
                        } else {
                            $html .= Html::encode($item->data ?? '');
                        }

                        break;
                }

                $html .= '</div>';

                $html .= '</div>';
                $html .= '</div>';
            }
        }

        return $html;
    }

    /**
     * @param $day
     * @return string
     */
    public function timelineDay($day)
    {
        $html = '<div class="timeline"><div class="time-label"><span class="bg-red">'.Html::encode($day).'</span></div>';
        $html .= $this->timelineItem($day);
        $html .= '</div>';

        return $html;
    }

    /**
     * @return string
     */
    public function run()
    {
        $html = '<section class="timeline">';
        $html .= '<div class="row"><div class="col-md-12">';

        foreach ($this->days as $day) {
            $html .= $this->timelineDay($day['created_date'] ?? '');
        }

        $html .= '</div></div>';
        $html .= '</section">';

        return $html;
    }
}
